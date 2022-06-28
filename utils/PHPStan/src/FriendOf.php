<?php

declare(strict_types=1);

namespace Utils\PHPStan;

use Attribute;

#[Attribute]
final class FriendOf
{
    /**
     * @param class-string $friendClass
     */
    public function __construct(
        public readonly string $friendClass
    ) {
    }
}
