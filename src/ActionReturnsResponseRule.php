<?php

declare(strict_types=1);

namespace Utils\PHPStan;

use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Node\InClassMethodNode;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Reflection\ParametersAcceptorSelector;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use PHPStan\Type\ObjectType;

/**
 * @implements \PHPStan\Rules\Rule<\PHPStan\Node\InClassMethodNode>
 */
final class ActionReturnsResponseRule implements Rule
{
    public function __construct(
        private readonly string $requiredReturnType,
        private readonly ControllerDetermination $determination,
    ) {
    }

    public function getNodeType(): string
    {
        return InClassMethodNode::class;
    }

    /**
     * @param InClassMethodNode $node
     */
    public function processNode(Node $node, Scope $scope): array
    {
        if (! $node->getOriginalNode()->isPublic()) {
            // The method is not a controller action
            return [];
        }

        if (
            ! $this->determination->isController($scope->getClassReflection())
        ) {
            // We're not inside a controller
            return [];
        }

        $methodReflection = $scope->getFunction();
        if (! $methodReflection instanceof MethodReflection) {
            /*
             * This shouldn't happen, since this rule subscribes
             * to `InClassMethodNode`...
             */
            return [];
        }

        $returnType = ParametersAcceptorSelector::selectSingle($methodReflection->getVariants())->getReturnType();

        if ((new ObjectType($this->requiredReturnType))
            ->isSuperTypeOf($returnType)
            ->yes()) {
            // The action already returns a Response
            return [];
        }

        return [
            RuleErrorBuilder::message(
                sprintf(
                    'Method %s::%s() should return %s',
                    $methodReflection->getDeclaringClass()
                        ->getName(),
                    $methodReflection->getName(),
                    $this->requiredReturnType,
                )
            )->build(),
        ];
    }
}
