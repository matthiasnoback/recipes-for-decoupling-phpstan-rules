<?php

declare(strict_types=1);

namespace Utils\PHPStan\Tests\ModelCreatedWithArrayRule;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use Utils\PHPStan\ModelCreatedWithArrayRule;

final class ModelCreatedWithArrayRuleTest extends RuleTestCase
{
    public function testModelCreatedWithArray(): void
    {
        $this->analyse(
            [__DIR__ . '/Fixtures/model-created-with-array.php'],
            [['Model is created with an array argument, use explicit arguments instead', 9]]
        );
    }

    public function testModelCreatedWithNoArguments(): void
    {
        $this->analyse(
            [__DIR__ . '/Fixtures/model-created-with-no-arguments.php'],
            [['Model is created with no arguments, use explicit arguments instead', 7]]
        );
    }

    public function testSkipModelCreatedWithMultipleArguments(): void
    {
        $this->analyse([__DIR__ . '/Fixtures/skip-model-created-with-multiple-arguments.php'], []);
    }

    public function testSkipModelCreatedWithNonArrayArgument(): void
    {
        $this->analyse([__DIR__ . '/Fixtures/skip-model-created-with-non-array-argument.php'], []);
    }

    public function testSkipNotAModel(): void
    {
        $this->analyse([__DIR__ . '/Fixtures/skip-not-a-model.php'], []);
    }

    public function testSkipNotCreate(): void
    {
        $this->analyse([__DIR__ . '/Fixtures/skip-not-create.php'], []);
    }

    protected function getRule(): Rule
    {
        return new ModelCreatedWithArrayRule();
    }
}
