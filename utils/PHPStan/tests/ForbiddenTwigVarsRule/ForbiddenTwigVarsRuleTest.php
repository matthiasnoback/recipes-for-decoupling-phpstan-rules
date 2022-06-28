<?php

declare(strict_types=1);

namespace Utils\PHPStan\Tests\ActionAllowedParameterRule;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use Utils\PHPStan\ForbiddenTwigVarsRule;

final class ForbiddenTwigVarsRuleTest extends RuleTestCase
{
    public function testTwigTemplateUsesForbiddenVar(): void
    {
        $this->analyse(
            [__DIR__ . '/Fixtures/twig-template-uses-forbidden-var.php'],
            [['Template uses forbidden var app', 1]]
        );
    }

    public function testSkipNotACallToTwigEnvironment(): void
    {
        $this->analyse([__DIR__ . '/Fixtures/skip-not-a-call-to-twig-environment.php'], []);
    }

    public function testSkipNotACallToRender(): void
    {
        $this->analyse([__DIR__ . '/Fixtures/skip-not-a-call-to-render.php'], []);
    }

    public function testSkipNotAConstantString(): void
    {
        $this->analyse([__DIR__ . '/Fixtures/skip-not-a-constant-string.php'], []);
    }

    public function testTemplateIsOkay(): void
    {
        $this->analyse([__DIR__ . '/Fixtures/skip-template-is-okay.php'], []);
    }

    protected function getRule(): Rule
    {
        return new ForbiddenTwigVarsRule(__DIR__ . '/Fixtures', ['app']);
    }
}
