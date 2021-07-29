<?php

namespace Permafrost\PhpCodeSearch;

use Permafrost\CodeSnippets\File;
use Permafrost\PhpCodeSearch\Results\FileSearchResults;
use Permafrost\PhpCodeSearch\Results\SearchError;
use Permafrost\PhpCodeSearch\Support\Arr;
use Permafrost\PhpCodeSearch\Support\NameResolver;
use Permafrost\PhpCodeSearch\Support\VirtualFile;
use Permafrost\PhpCodeSearch\Visitors\AssignmentVisitor;
use Permafrost\PhpCodeSearch\Visitors\FunctionCallVisitor;
use Permafrost\PhpCodeSearch\Visitors\FunctionDefinitionVisitor;
use Permafrost\PhpCodeSearch\Visitors\MethodCallVisitor;
use Permafrost\PhpCodeSearch\Visitors\NewClassVisitor;
use Permafrost\PhpCodeSearch\Visitors\StaticCallVisitor;
use Permafrost\PhpCodeSearch\Visitors\StaticPropertyVisitor;
use Permafrost\PhpCodeSearch\Visitors\VariableReferenceVisitor;
use PhpParser\Error;
use PhpParser\Node;
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
    protected $assignments = [];

    /** @var array */
    protected $classes = [];

    /** @var array */
    protected $functions = [];

    /** @var array */
    protected $methods = [];

    /** @var array */
    protected $static = [];

    /** @var array */
    protected $variables = [];

    /** @var bool */
    protected $withSnippets = true;

    public function __construct($parser = null)
    {
        $this->parser = $parser ?? (new ParserFactory())->create(ParserFactory::PREFER_PHP7);
    }

    public function assignments(array $varNames): self
    {
        $this->assignments = array_merge($this->assignments, $varNames);

        return $this;
    }

    public function classes(array $names): self
    {
        $this->classes = array_merge($this->classes, $names);

        return $this;
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

    public function static(array $names): self
    {
        $this->static = array_merge($this->static, $names);

        return $this;
    }

    public function variables(array $names): self
    {
        $this->variables = array_merge($this->variables, $names);

        return $this;
    }

    public function withoutSnippets(): self
    {
        $this->withSnippets = false;

        return $this;
    }

    /**
     * @param File|VirtualFile|string $file
     * @return FileSearchResults
     */
    public function search($file): FileSearchResults
    {
        $file = is_string($file) ? new File($file) : $file;

        $results = new FileSearchResults($file, $this->withSnippets);

        if (! $this->parseFile($file, $results)) {
            return $results;
        }

        $this->traverseNodes(
            $results,
            $this->findAllReferences($this->ast)
        );

        return $results;
    }

    public function searchCode(string $code): FileSearchResults
    {
        return $this->search(new VirtualFile($code));
    }

    /**
     * @param \Permafrost\PhpCodeSearch\Support\File|\Permafrost\CodeSnippets\File $file
     * @param FileSearchResults $results
     * @return bool
     */
    protected function parseFile($file, FileSearchResults $results): bool
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
        $nodeMap = [
            Node\Expr\Assign::class => $this->assignments,
            Node\Expr\FuncCall::class => $this->functions,
            Node\Expr\MethodCall::class => $this->methods,
            Node\Expr\New_::class => $this->classes,
            Node\Expr\StaticCall::class => $this->static,
            Node\Expr\StaticPropertyFetch::class => $this->static,
            Node\Expr\Variable::class => $this->variables,
            Node\Stmt\Function_::class => $this->functions,
        ];

        $result = [];

        foreach ($nodeMap as $parserNodeClass => $names) {
            $result[] = $this->findReferences($ast, $parserNodeClass, $names);
        }

        return $this->sortNodesByLineNumber(...$result);
    }

    protected function findReferences(array $ast, string $class, array $names): array
    {
        $nodes = (new NodeFinder())->findInstanceOf($ast, $class);

        return collect($nodes)->filter(function (Node $node) use ($names) {
            $name = NameResolver::resolve($node) ?? false;

            return $name && Arr::matchesAny($name, $names, true);
        })->all();
    }

    protected function traverseNodes(FileSearchResults $results, array $nodes): void
    {
        $traverser = new NodeTraverser();

        $traverser->addVisitor(new AssignmentVisitor($results, $this->assignments));
        $traverser->addVisitor(new FunctionCallVisitor($results, $this->functions));
        $traverser->addVisitor(new FunctionDefinitionVisitor($results, $this->functions));
        $traverser->addVisitor(new MethodCallVisitor($results, $this->methods));
        $traverser->addVisitor(new NewClassVisitor($results, $this->classes));
        $traverser->addVisitor(new StaticCallVisitor($results, $this->static));
        $traverser->addVisitor(new StaticPropertyVisitor($results, $this->static));
        $traverser->addVisitor(new VariableReferenceVisitor($results, $this->variables));

        $traverser->traverse($nodes);
    }

    protected function sortNodesByLineNumber(...$items): array
    {
        $result = array_merge(...$items);

        usort($result, function ($aNode, $bNode) {
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
