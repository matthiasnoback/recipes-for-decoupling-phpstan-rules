<?php

declare(strict_types=1);

namespace Utils\PHPStan\Tests\FriendRule\Fixtures;

use Utils\PHPStan\FriendOf;

final class ClassWithFriendAttribute
{
    #[FriendOf(ATrueFriend::class)]
    public function internalMethod(): array
    {
        return [
            // ...
        ];
    }
}
