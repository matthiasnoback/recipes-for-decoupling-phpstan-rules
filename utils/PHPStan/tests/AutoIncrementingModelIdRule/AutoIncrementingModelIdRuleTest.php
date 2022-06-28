<?php

declare(strict_types=1);

namespace Utils\PHPStan\Tests\AutoIncrementingModelIdRule;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use Utils\PHPStan\AutoIncrementingModelIdRule;

final class AutoIncrementingModelIdRuleTest extends RuleTestCase
{
    public function testModelHasAutoIncrementingId(): void
    {
        $this->analyse(
            [__DIR__ . '/Fixtures/ModelHasAutoIncrementingId.php'],
            [['This model has an auto-incrementing ID', 9]]
        );
    }

    public function testModelHasImplicitAutoIncrementingId(): void
    {
        $this->analyse(
            [__DIR__ . '/Fixtures/ModelHasImplicitAutoIncrementingId.php'],
            [['This model has an auto-incrementing ID', 9]]
        );
    }

    public function testSkipModelHasNonAutoIncrementingId(): void
    {
        $this->analyse([__DIR__ . '/Fixtures/SkipModelHasNonAutoIncrementingId.php'], []);
    }

    public function testSkipNotAModel(): void
    {
        $this->analyse([__DIR__ . '/Fixtures/SkipNotAModel.php'], []);
    }

    protected function getRule(): Rule
    {
        return new AutoIncrementingModelIdRule();
    }
}
