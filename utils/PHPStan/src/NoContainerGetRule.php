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
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @implements \PHPStan\Rules\Rule<\PhpParser\Node\Expr\MethodCall>
 */
final class NoContainerGetRule implements Rule
{
    public function __construct(
        private readonly ControllerDetermination $determination,
    ) {
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
        $objectType = $scope->getType($node->var);
        $containerType = new ObjectType(ContainerInterface::class);

        if (! $containerType->isSuperTypeOf($objectType)->yes()) {
            return [];
        }

        if (! $node->name instanceof Identifier) {
            // This is a dynamic method call, let's ignore it
            return [];
        }

        if ($node->name->name !== 'get') {
            // Not a call to `ContainerInterface::get()`, ignore it
            return [];
        }

        if ($scope->isInClass()
            && $this->determination->isController($scope->getClassReflection())) {
            /*
             * We're allowed to call `ContainerInterface::get()`
             * inside controllers
             */
            return [];
        }

        return [RuleErrorBuilder::message('Don\'t use the container as a service locator')->build()];
    }
}
