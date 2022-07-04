<?php

declare(strict_types=1);

namespace Utils\PHPStan\Tests\ForbiddenImplementsRule;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Runner\BeforeFirstTestHook;
use Utils\PHPStan\ForbiddenImplementsRule;

final class ForbiddenImplementsRuleTest extends RuleTestCase
{
    public function testClassImplementsForbiddenInterface(): void
    {
        $this->analyse(
            [__DIR__ . '/Fixtures/class-implements-forbidden-interface.php'],
            [['Class implements forbidden interface PHPUnit\Runner\BeforeFirstTestHook', 9]]
        );
    }

    public function testSkipClassImplementsAllowedInterface(): void
    {
        $this->analyse([__DIR__ . '/Fixtures/skip-class-implements-allowed-interface.php'], []);
    }

    protected function getRule(): Rule
    {
        return new ForbiddenImplementsRule(BeforeFirstTestHook::class);
    }
}
