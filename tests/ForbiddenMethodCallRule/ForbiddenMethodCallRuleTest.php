<?php

declare(strict_types=1);

namespace Utils\PHPStan\Tests\ForbiddenMethodCallRule;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\TestCase;
use Utils\PHPStan\ForbiddenMethodCallRule;

final class ForbiddenMethodCallRuleTest extends RuleTestCase
{
    public function testCallToForbiddenMethod(): void
    {
        $this->analyse(
            [__DIR__ . '/Fixtures/CallToForbiddenMethod.php'],
            [['Call to forbidden method PHPUnit\Framework\TestCase::createMock()', 13]]
        );
    }

    public function testSkipDifferentMethod(): void
    {
        $this->analyse([__DIR__ . '/Fixtures/SkipDifferentMethod.php'], []);
    }

    public function testSkipDifferentClass(): void
    {
        $this->analyse([__DIR__ . '/Fixtures/SkipDifferentClass.php'], []);
    }

    protected function getRule(): Rule
    {
        return new ForbiddenMethodCallRule(TestCase::class, 'createMock',);
    }
}
