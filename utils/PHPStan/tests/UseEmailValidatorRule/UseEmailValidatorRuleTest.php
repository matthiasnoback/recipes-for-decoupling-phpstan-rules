<?php

declare(strict_types=1);

namespace Utils\PHPStan\Tests\UseEmailValidatorRule;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use Utils\PHPStan\UseEmailValidatorRule;

final class UseEmailValidatorRuleTest extends RuleTestCase
{
    public function testValidateStringContainsEmail(): void
    {
        $this->analyse(
            [__DIR__ . '/Fixtures/validate-string-contains-email.php'],
            [['Use App\Models\Email::validator() instead of "email"', 11]]
        );
    }

    public function testSkipValidateHasNoStrings(): void
    {
        $this->analyse([__DIR__ . '/Fixtures/skip-validate-has-no-strings.php'], []);
    }

    public function testSkipValidateStringDoesNotContainEmail(): void
    {
        $this->analyse([__DIR__ . '/Fixtures/skip-validate-string-does-not-contain-email.php'], []);
    }

    protected function getRule(): Rule
    {
        return new UseEmailValidatorRule();
    }
}
