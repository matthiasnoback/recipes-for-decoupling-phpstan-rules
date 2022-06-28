<?php

declare(strict_types=1);

namespace Utils\PHPStan;

use Twig\Environment;
use Twig\Node\Expression\FunctionExpression;
use Twig\Node\Node;
use Twig\NodeVisitor\NodeVisitorInterface;

final class CollectFunctionCalls implements NodeVisitorInterface
{
    /**
     * @var array<FunctionExpression>
     */
    private array $functionCalls = [];

    public function enterNode(Node $node, Environment $env): Node
    {
        if ($node instanceof FunctionExpression) {
            $this->functionCalls[] = $node;
        }
        return $node;
    }

    public function functionCalls(): array
    {
        return $this->functionCalls;
    }

    public function leaveNode(Node $node, Environment $env): ?Node
    {
        return $node;
    }

    public function getPriority(): int
    {
        return 10;
    }
}
