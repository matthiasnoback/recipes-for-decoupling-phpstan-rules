<?php

declare(strict_types=1);

namespace Utils\PHPStan;

use Twig\Environment;
use Twig\Node\Expression\NameExpression;
use Twig\Node\Node;
use Twig\NodeVisitor\NodeVisitorInterface;

final class CollectVariableNamesVisitor implements NodeVisitorInterface
{
    /**
     * @var array<NameExpression>
     */
    private array $variableNames = [];

    public function enterNode(Node $node, Environment $env): Node
    {
        if ($node instanceof NameExpression) {
            $this->variableNames[] = $node;
        }
        return $node;
    }

    public function variableNames(): array
    {
        return $this->variableNames;
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
