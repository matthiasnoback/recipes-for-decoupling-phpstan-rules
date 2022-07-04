<?php

declare(strict_types=1);

namespace Utils\PHPStan\Tests\NoErrorSilencingRule;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use Utils\PHPStan\NoErrorSilencingRule;

final class NoErrorSilencingRuleTest extends RuleTestCase
{
    public function testRule(): void
    {
        $this->analyse(
            [__DIR__ . '/Fixtures/error-silencing.php'],
            [['You should not use the silencing operator (@)', 5]]
        );
    }

    protected function getRule(): Rule
    {
        return new NoErrorSilencingRule();
    }
}
