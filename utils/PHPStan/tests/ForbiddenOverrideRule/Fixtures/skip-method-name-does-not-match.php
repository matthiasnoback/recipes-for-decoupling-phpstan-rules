<?php

declare(strict_types=1);

namespace Utils\PHPStan\Tests\ForbiddenClassRule\Fixtures;

use PHPUnit\Framework\TestCase;

class MethodNameDoesNotMatch extends TestCase
{
    public function someMethod()
    {
    }
}
