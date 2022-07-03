<?php

declare(strict_types=1);

namespace Utils\PHPStan;

use PhpParser\Node;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * @implements \PHPStan\Rules\Rule<\PhpParser\Node\Stmt\ClassMethod>
 */
final class ForbiddenOverrideRule implements Rule
{
    public function __construct(
        private readonly string $overrideFromClass,
        private readonly string $overrideMethod,
    ) {
    }

    public function getNodeType(): string
    {
        return ClassMethod::class;
    }

    /**
     * @param ClassMethod $node
     */
    public function processNode(Node $node, Scope $scope): array
    {
        if ($node->name->toString() !== $this->overrideMethod) {
            // The method name does not match
            return [];
        }

        if (! in_array($this->overrideFromClass, $scope->getClassReflection() ->getParentClassesNames(), true)) {
            // The method name matches, but not the class
            return [];
        }

        return [
            RuleErrorBuilder::message(
                sprintf(
                    'Overriding method %s::%s() is not allowed',
                    $this->overrideFromClass,
                    $this->overrideMethod,
                )
            )->build(),
        ];
    }
}
