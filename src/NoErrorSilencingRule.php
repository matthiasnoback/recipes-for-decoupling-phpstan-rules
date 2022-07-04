<?php

declare(strict_types=1);

namespace Utils\PHPStan;

use PhpParser\Node;
use PhpParser\Node\Expr\ErrorSuppress;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * @implements \PHPStan\Rules\Rule<\PhpParser\Node\Expr\ErrorSuppress>
 */
final class NoErrorSilencingRule implements Rule
{
    public function getNodeType(): string
    {
        return ErrorSuppress::class;
    }

    public function processNode(Node $node, Scope $scope): array
    {
        return [RuleErrorBuilder::message('You should not use the silencing operator (@)')->build()];
    }
}
