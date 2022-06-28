<?php

declare(strict_types=1);

namespace Utils\PHPStan\Tests\ForbiddenOverrideRule;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\TestCase;
use Utils\PHPStan\ForbiddenOverrideRule;

final class ForbiddenOverrideRuleTest extends RuleTestCase
{
    public function testClassHasForbiddenOverride(): void
    {
        $this->analyse(
            [__DIR__ . '/Fixtures/class-has-forbidden-override.php'],
            [['Overriding method PHPUnit\Framework\TestCase::setUpBeforeClass() is not allowed', 11]]
        );
    }

    public function testSkipAncestorDoesNotMatch(): void
    {
        $this->analyse([__DIR__ . '/Fixtures/skip-ancestor-does-not-match.php'], []);
    }

    public function testSkipMethodNameDoesNotMatch(): void
    {
        $this->analyse([__DIR__ . '/Fixtures/skip-method-name-does-not-match.php'], []);
    }

    protected function getRule(): Rule
    {
        return new ForbiddenOverrideRule(TestCase::class, 'setUpBeforeClass');
    }
}
