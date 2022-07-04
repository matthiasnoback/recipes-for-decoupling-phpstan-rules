<?php

declare(strict_types=1);

namespace Utils\PHPStan\Tests\FriendRule\Fixtures;

final class NotAFriend
{
    public function someMethod()
    {
        $object = new ClassWithFriendAttribute();

        $object->internalMethod();
    }
}
