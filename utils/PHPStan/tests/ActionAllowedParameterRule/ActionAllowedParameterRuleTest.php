<?php

declare(strict_types=1);

namespace Utils\PHPStan\Tests\ActionAllowedParameterRule;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use Symfony\Component\HttpFoundation\Request;
use Utils\PHPStan\ActionAllowedParameterRule;
use Utils\PHPStan\DeterminationBasedOnSuffix;

final class ActionAllowedParameterRuleTest extends RuleTestCase
{
    public function testRule(): void
    {
        $this->analyse(
            [__DIR__ . '/Fixtures/parameter-type-not-allowed.php'],
            [
                [
                    'Controller actions can only have parameters of type "Symfony\Component\HttpFoundation\Request"',
                    11,
                ],
            ]
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

    public function testActionOnlyHasAllowedParameter(): void
    {
        $this->analyse([__DIR__ . '/Fixtures/skip-action-only-has-allowed-parameter.php'], []);
    }

    protected function getRule(): Rule
    {
        return new ActionAllowedParameterRule(Request::class, new DeterminationBasedOnSuffix('Controller'),);
    }
}
