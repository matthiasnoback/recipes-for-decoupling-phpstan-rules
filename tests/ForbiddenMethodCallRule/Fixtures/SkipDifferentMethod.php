<?php

declare(strict_types=1);

namespace Utils\PHPStan\Tests\ForbiddenMethodCallRule\Fixtures;

use PHPUnit\Framework\TestCase;

class SkipDifferentMethod extends TestCase
{
    public function test(): void
    {
        $this->assertTrue();
    }
}
