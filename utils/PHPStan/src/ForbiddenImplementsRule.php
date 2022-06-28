<?php

declare(strict_types=1);

namespace Utils\PHPStan;

use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Node\InClassNode;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

final class ForbiddenImplementsRule implements Rule
{
    private string $forbiddenInterface;

    public function __construct(string $forbiddenInterface)
    {
        $this->forbiddenInterface = $forbiddenInterface;
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
        if (! $node->getClassReflection()->implementsInterface($this->forbiddenInterface)) {
            return [];
        }

        return [
            RuleErrorBuilder::message(
                sprintf('Class implements forbidden interface %s', $this->forbiddenInterface,)
            )->build(),
        ];
    }
}
