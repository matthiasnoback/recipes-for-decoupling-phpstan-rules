<?php

declare(strict_types=1);

namespace Utils\PHPStan;

use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Node\InClassMethodNode;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use PHPStan\Type\ObjectType;

/**
 * @implements \PHPStan\Rules\Rule<\PHPStan\Node\InClassMethodNode>
 */
final class ActionAllowedParameterRule implements Rule
{
    public function __construct(
        private readonly string $allowedParameterType,
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

        $errors = [];

        foreach ($node->getOriginalNode()->params as $param) {
            if (

                ! (new ObjectType($this->allowedParameterType))
                    ->isSuperTypeOf($scope->getType($param->var))
                    ->yes()
                            ) {
                $errors[] =
                    RuleErrorBuilder::message(
                        sprintf(
                            'Controller actions can only have parameters of type "%s"',
                            $this->allowedParameterType,
                        )
                    )->build();
            }
        }

        return $errors;
    }
}
