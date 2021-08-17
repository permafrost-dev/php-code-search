<?php

namespace Permafrost\PhpCodeSearch\Results\Nodes;

use Permafrost\PhpCodeSearch\Code\GenericCodeLocation;
use Permafrost\PhpCodeSearch\Results\Nodes\Traits\HasLocation;
use Permafrost\PhpCodeSearch\Results\Nodes\Traits\HasName;
use Permafrost\PhpCodeSearch\Support\ExpressionTransformer;
use PhpParser\Node;

class ParameterNode implements ResultNode
{
    use HasLocation;
    use HasName;

    /** @var string|null */
    public $type;

    /** @var array|mixed|ArrayItemNode|ArrayNode|AssignmentNode|BinaryOperationNode|FunctionCallNode|MethodCallNode|PropertyAccessNode|Scalar\NumberNode|Scalar\StringNode|StaticMethodCallNode|StaticPropertyAccessNode|VariableNode|Node\Arg|null */
    public $default;

    public function __construct(Node\Param $node)
    {
        $this->name = $node->var->name;

        if ($node->type instanceof Node\NullableType) {
            $this->type = $node->getType();
        } else {
            $this->type = optional($node->type)->toString();
        }

        $this->default = $node->default ? ExpressionTransformer::parserNodeToResultNode($node->default) : null;
        $this->location = GenericCodeLocation::createFromNode($node);
    }

    public static function create(Node\Param $node): self
    {
        return new static(...func_get_args());
    }

    public function defaultValue()
    {
        return $this->default;
    }
}
