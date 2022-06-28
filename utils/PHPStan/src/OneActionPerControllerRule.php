<?php

declare(strict_types=1);

namespace Utils\PHPStan;

use PhpParser\Node;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Analyser\Scope;
use PHPStan\Node\InClassNode;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

final class OneActionPerControllerRule implements Rule
{
    public function __construct(
        private readonly ControllerDetermination $determination,
    ) {
    }

    public function getNodeType(): string
    {
        return InClassNode::class;
    }

    /**
     * @param InClassNode $node
     */
    public function processNode(Node $node, Scope $scope): array
    {
        if (
            ! $this->determination->isController($node->getClassReflection())
        ) {
            // This class is not a controller
            return [];
        }

        $actionMethods = array_filter(
            $node->getOriginalNode()
                ->getMethods(),
            fn (ClassMethod $method) => $method->isPublic()
        );

        if (count($actionMethods) > 1) {
            return [
                RuleErrorBuilder::message(
                    sprintf('Controller %s should have only one action', $scope->getClassReflection() ->getName(),)
                )->build(),
            ];
        }

        return [];
    }
}
