<?php

declare(strict_types=1);

namespace Utils\PHPStan\Tests\ForbiddenMethodCallRule\Fixtures;

class SkipDifferentClass
{
    public function test(): void
    {
        $this->createMock();
    }
}
