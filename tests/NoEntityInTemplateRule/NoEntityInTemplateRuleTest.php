<?php

declare(strict_types=1);

namespace Utils\PHPStan\Tests\NoEntityInTemplateRule;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use Utils\PHPStan\DoctrineEntityWithAnnotation;
use Utils\PHPStan\NoEntityInTemplateRule;

final class NoEntityInTemplateRuleTest extends RuleTestCase
{
    public function testEntityPassedToTwig(): void
    {
        $this->analyse(
            [__DIR__ . '/Fixtures/entity-passed-to-twig.php'],
            [[
                'Entity of type Utils\PHPStan\Tests\NoEntityInTemplateRule\Fixtures\AnEntity should not be passed to a template',
                13,
            ]]
        );
    }

    public function testSkipNoEntityPassed(): void
    {
        $this->analyse([__DIR__ . '/Fixtures/skip-no-entity-passed.php'], []);
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

    protected function getRule(): Rule
    {
        return new NoEntityInTemplateRule(new DoctrineEntityWithAnnotation());
    }
}
