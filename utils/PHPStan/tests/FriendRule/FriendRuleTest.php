<?php

declare(strict_types=1);

namespace Utils\PHPStan\Tests\FriendRule;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use Utils\PHPStan\FriendRule;

final class FriendRuleTest extends RuleTestCase
{
    public function testNotAFriend(): void
    {
        $this->analyse(
            [__DIR__ . '/Fixtures/NotAFriend.php'],
            [
                [
                    'Method call Utils\PHPStan\Tests\FriendRule\Fixtures\ClassWithFriendAttribute::internalMethod() is only allowed inside friend classes',
                    13,
                ],
            ]
        );
    }

    public function testSkipATrueFriend(): void
    {
        $this->analyse([__DIR__ . '/Fixtures/ATrueFriend.php'], []);
    }

    public static function getAdditionalConfigFiles(): array
    {
        return [__DIR__ . '/../../src/FriendRule.neon'];
    }

    protected function getRule(): Rule
    {
        return self::getContainer()->getByType(FriendRule::class);
    }
}
