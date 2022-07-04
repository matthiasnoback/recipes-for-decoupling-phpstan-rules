<?php

declare(strict_types=1);

namespace Utils\PHPStan;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * @implements \PHPStan\Rules\Rule<\PhpParser\Node\Stmt\Class_>
 */
final class ForbiddenParentClassRule implements Rule
{
    public function __construct(
        private readonly string $forbiddenParentClass,
    ) {
    }

    public function getNodeType(): string
    {
        return Class_::class;
    }

    /**
     * @param Class_ $node
     */
    public function processNode(Node $node, Scope $scope): array
    {
        if ($node->extends === null) {
            // This class does not `extend` anything
            return [];
        }

        if (
            $node->extends->toString() !== $this->forbiddenParentClass
        ) {
            // The extended class is not the forbidden parent class
            return [];
        }

        return [
            RuleErrorBuilder::message(
                sprintf('Parent class %s is forbidden', $this->forbiddenParentClass,),
            )->build(),
        ];
    }
}
