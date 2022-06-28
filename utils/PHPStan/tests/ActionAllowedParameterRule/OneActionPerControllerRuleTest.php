<?php

declare(strict_types=1);

namespace Utils\PHPStan\Tests\OneActionPerControllerRule;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use Utils\PHPStan\DeterminationBasedOnSuffix;
use Utils\PHPStan\OneActionPerControllerRule;

final class OneActionPerControllerRuleTest extends RuleTestCase
{
    public function testControllerHasMoreThanOneAction(): void
    {
        $this->analyse(
            [__DIR__ . '/Fixtures/controller-has-more-than-one-action.php'],
            [['Controller SomeController should have only one action', 8]]
        );
    }

    public function testSkipControllerHasOneAction(): void
    {
        $this->analyse([__DIR__ . '/Fixtures/skip-controller-has-one-action.php'], []);
    }

    public function testSkipControllerHasNoActions(): void
    {
        $this->analyse([__DIR__ . '/Fixtures/skip-controller-has-no-actions.php'], []);
    }

    public function testSkipNotAController(): void
    {
        $this->analyse([__DIR__ . '/Fixtures/skip-not-a-controller.php'], []);
    }

    public function testSkipControllerHasPrivateMethods(): void
    {
        $this->analyse([__DIR__ . '/Fixtures/skip-controller-has-private-methods.php'], []);
    }

    protected function getRule(): Rule
    {
        return new OneActionPerControllerRule(new DeterminationBasedOnSuffix('Controller'),);
    }
}
