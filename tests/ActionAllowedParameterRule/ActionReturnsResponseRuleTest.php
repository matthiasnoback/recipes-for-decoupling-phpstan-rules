<?php

declare(strict_types=1);

namespace Utils\PHPStan\Tests\ActionAllowedParameterRule;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use Symfony\Component\HttpFoundation\Response;
use Utils\PHPStan\ActionReturnsResponseRule;
use Utils\PHPStan\DeterminationBasedOnSuffix;

final class ActionReturnsResponseRuleTest extends RuleTestCase
{
    public function testRule(): void
    {
        $this->analyse(
            [__DIR__ . '/Fixtures/action-does-not-return-response.php'],
            [['Method SomeController::someAction() should return Symfony\Component\HttpFoundation\Response', 9]]
        );
    }

    public function testSkipNotAPublicMethod(): void
    {
        $this->analyse([__DIR__ . '/Fixtures/skip-not-a-public-method.php'], []);
    }

    public function testSkipNotAController(): void
    {
        $this->analyse([__DIR__ . '/Fixtures/skip-not-a-controller.php'], []);
    }

    public function testSkipActionReturnsResponse(): void
    {
        $this->analyse([__DIR__ . '/Fixtures/skip-action-returns-response.php'], []);
    }

    protected function getRule(): Rule
    {
        return new ActionReturnsResponseRule(Response::class, new DeterminationBasedOnSuffix('Controller'),);
    }
}
