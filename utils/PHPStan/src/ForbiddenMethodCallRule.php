<?php

declare(strict_types=1);

namespace Utils\PHPStan;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Identifier;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use PHPStan\Type\ObjectType;

final class ForbiddenMethodCallRule implements Rule
{
    private ObjectType $class;

    public function __construct(
        string $class,
        private readonly string $method,
    ) {
        $this->class = new ObjectType($class);
    }

    public function getNodeType(): string
    {
        return MethodCall::class;
    }

    /**
     * @param MethodCall $node
     */
    public function processNode(Node $node, Scope $scope): array
    {
        if (! $node->name instanceof Identifier) {
            // Dynamic method name, can not be analyzed
            return [];
        }

        if ($node->name->toString() !== $this->method) {
            // The method is a different one
            return [];
        }

        if (! $this->class->isSuperTypeOf($scope->getType($node->var))->yes()
        ) {
            // The class does not match the expected type
            return [];
        }

        return [
            RuleErrorBuilder::message(
                sprintf('Call to forbidden method %s::%s()', $this->class->getClassName(), $this->method,)
            )->build(),
        ];
    }
}
