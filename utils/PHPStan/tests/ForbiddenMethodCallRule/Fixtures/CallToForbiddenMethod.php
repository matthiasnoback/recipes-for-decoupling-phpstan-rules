<?php

declare(strict_types=1);

namespace Utils\PHPStan\Tests\ForbiddenMethodCallRule\Fixtures;

use PHPUnit\Framework\TestCase;

class CallToForbiddenMethod extends TestCase
{
    public function test(): void
    {
        $this->createMock();
    }
}
