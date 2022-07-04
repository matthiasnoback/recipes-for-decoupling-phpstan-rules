<?php

declare(strict_types=1);

namespace Utils\PHPStan\Tests\NoContainerGetRule;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use Utils\PHPStan\NoContainerGetRule;

final class NoContainerGetRuleTest extends RuleTestCase
{
    public function testRule(): void
    {
        $this->analyse(
            [__DIR__ . '/Fixtures/container-get.php'],
            [['Don\'t use the container as a service locator', 9]]
        );
    }

    public function testSkipOtherMethods(): void
    {
        $this->analyse(
            [__DIR__ . '/Fixtures/skip-different-method.php'],
            [
                // we expect no errors
            ]
        );
    }

    public function testSkipOtherObjects(): void
    {
        $this->analyse(
            [__DIR__ . '/Fixtures/skip-different-object.php'],
            [
                // we expect no errors
            ]
        );
    }

    public function testSkipContainerGetInController(): void
    {
        $this->analyse(
            [__DIR__ . '/Fixtures/skip-container-get-in-controller.php'],
            [
                // we expect no errors
            ]
        );
    }

    public static function getAdditionalConfigFiles(): array
    {
        return [__DIR__ . '/phpstan.neon'];
    }

    protected function getRule(): Rule
    {
        return self::getContainer()->getByType(NoContainerGetRule::class,);
    }
}
