<?php

namespace Permafrost\PhpCodeSearch;

use Permafrost\PhpCodeSearch\Results\FileSearchResults;
use Permafrost\PhpCodeSearch\Results\SearchError;
use Permafrost\PhpCodeSearch\Support\Arr;
use Permafrost\PhpCodeSearch\Support\File;
use Permafrost\PhpCodeSearch\Support\VirtualFile;
use Permafrost\PhpCodeSearch\Visitors\AssignmentVisitor;
use Permafrost\PhpCodeSearch\Visitors\FunctionCallVisitor;
use Permafrost\PhpCodeSearch\Visitors\MethodCallVisitor;
use Permafrost\PhpCodeSearch\Visitors\NewClassVisitor;
use Permafrost\PhpCodeSearch\Visitors\StaticCallVisitor;
use Permafrost\PhpCodeSearch\Visitors\VariableReferenceVisitor;
use PhpParser\Error;
use PhpParser\Node;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Stmt;
use PhpParser\NodeFinder;
use PhpParser\NodeTraverser;
use PhpParser\ParserFactory;

class Searcher
{
    /** @var ParserFactory */
    protected $parser;

    /** @var array */
    protected $ast = [];

    /** @var array */
    protected $functions = [];

    /** @var array */
    protected $methods = [];

    /** @var array */
    protected $classes = [];

    /** @var array */
    protected $static = [];

    /** @var array */
    protected $assignments = [];

    /** @var array */
    protected $variables = [];

    /** @var bool */
    protected $withSnippets = true;

    public function __construct($parser = null)
    {
        $this->parser = $parser ?? (new ParserFactory())->create(ParserFactory::PREFER_PHP7);
    }

    public function functions(array $names): self
    {
        $this->functions = array_merge($this->functions, $names);

        return $this;
    }

    public function methods(array $names): self
    {
        $this->methods = array_merge($this->methods, $names);

        return $this;
    }

    public function variables(array $names): self
    {
        $this->variables = array_merge($this->variables, $names);

        return $this;
    }

    public function static(array $names): self
    {
        $this->static = array_merge($this->static, $names);

        return $this;
    }

    public function assignments(array $varNames): self
    {
        $varNames = array_map(function ($item) {
            return ltrim($item, '$');
        }, $varNames);

        $this->assignments = array_merge($this->assignments, $varNames);

        return $this;
    }

    public function classes(array $names): self
    {
        $this->classes = $names;

        return $this;
    }

    public function withoutSnippets(): self
    {
        $this->withSnippets = false;

        return $this;
    }

    /**
     * @param File|string $file
     * @return FileSearchResults
     */
    public function search($file): FileSearchResults
    {
        if (is_string($file)) {
            $file = new File($file);
        }

        $results = new FileSearchResults($file, $this->withSnippets);

        if (! $this->parseFile($file, $results)) {
            return $results;
        }

        $calls = $this->findAllReferences($this->ast);

        $this->traverseNodes($results, $calls);

        return $results;
    }

    public function searchCode(string $code): FileSearchResults
    {
        $file = new VirtualFile($code);

        return $this->search($file);
    }

    protected function parseFile(File $file, FileSearchResults $results): bool
    {
        try {
            /** @var array|Stmt[] $ast */
            $this->ast = $this->parser->parse($file->contents());
        } catch (Error $error) {
            $results->addError(new SearchError($error, "Parse error: {$error->getMessage()}"));

            return false;
        }

        return true;
    }

    protected function findAllReferences(array $ast): array
    {
        $staticMethodCalls = $this->findReferences($ast, Node\Expr\StaticCall::class, 'class', $this->static);
        $functionCalls = $this->findReferences($ast, FuncCall::class, 'name', $this->functions);
        $assignments = $this->findReferences($ast, Node\Expr\Assign::class, 'var', $this->assignments);
        $classes = $this->findReferences($ast, Node\Expr\New_::class, 'class', $this->classes);
        $methods = $this->findReferences($ast, Node\Expr\MethodCall::class, 'name', $this->methods);
        $variables = $this->findReferences($ast, Node\Expr\Variable::class, 'name', $this->variables);

        return $this->sortNodesByLineNumber(
            $functionCalls,
            $classes,
            $staticMethodCalls,
            $assignments,
            $methods,
            $variables
        );
    }

    protected function findReferences(array $ast, string $class, ?string $nodeNameProp, array $names): array
    {
        $nodeFinder = new NodeFinder();

        $nodes = $nodeFinder->findInstanceOf($ast, $class);

        if (! $nodeNameProp) {
            return $nodes;
        }

        return array_filter($nodes, function (Node $node) use ($names, $nodeNameProp) {
            $name = '';

            if ($node instanceof FuncCall) {
                $name = $node->name->toString();

                return Arr::matches($name, $names, true);
            }

            if ($node instanceof Node\Expr\MethodCall) {
                $name = $node->name->toString();

                return Arr::matches($name, $names, true);
            }

            if ($node instanceof Node\Expr\StaticCall) {
                $name = $node->class->toString();
                $methodName = $node->name->toString();

                return Arr::matches($name, $names, true) || Arr::matches("{$name}::{$methodName}", $names, true);
                //return NodeSearch::containsStaticCallName($node, $names);
            }

            if ($node instanceof Node\Expr\Variable) {
                $name = $node->name;

                return Arr::matches($name, $names, true);
            }

            if ($node instanceof Node\Expr\Array_) {
                return false;
            }

            if ($node instanceof Node\Expr\ArrayItem) {
                return false;
            }

            if ($node instanceof Node\Expr\ArrayDimFetch) {
                $name = $node->var->name;

                return Arr::matches($name, $names, true);
            }

//            if ($node instanceof Node\Expr\New_) {
//                $name = $node->class->name->name;
//            }

            if ($node instanceof Node\Expr\Assign) {
                if (! $node->var instanceof Node\Expr\Variable) {
                    return false;
                }

                $name = $node->var->name;
            }

            if (! empty($name)) {
                return in_array($name, $names, true);
            }

            if (isset($node->{$nodeNameProp}->name)) {
                return in_array($node->{$nodeNameProp}->name, $names, true);
            }

            if (! isset($node->{$nodeNameProp}->parts)) {
                return false;
            }

            return in_array($node->{$nodeNameProp}->parts[0], $names, true);
        });
    }

    protected function traverseNodes(FileSearchResults $results, array $nodes): void
    {
        $traverser = new NodeTraverser();

        $traverser->addVisitor(new FunctionCallVisitor($results, $this->functions));
        $traverser->addVisitor(new StaticCallVisitor($results, $this->static));
        $traverser->addVisitor(new MethodCallVisitor($results, $this->methods));
        $traverser->addVisitor(new VariableReferenceVisitor($results, $this->variables));
        $traverser->addVisitor(new NewClassVisitor($results, $this->classes));
        $traverser->addVisitor(new AssignmentVisitor($results, $this->assignments));

        $traverser->traverse($nodes);
    }

    protected function sortNodesByLineNumber(...$items)
    {
        $result = array_merge(...$items);

        usort($result, function ($a, $b) {
            if ($a instanceof Node\Expr\Variable) {
                $aNode = $a;
                $bNode = $b;
            } else {
                $aNode = $a->name;
                $bNode = $b->name;
            }


            if ($aNode->getAttribute('startLine') > $bNode->getAttribute('startLine')) {
                return 1;
            }

            if ($aNode->getAttribute('startLine') < $bNode->getAttribute('startLine')) {
                return -1;
            }

            return 0;
        });

        return $result;
    }
}
