<?php

declare(strict_types=1);

namespace Utils\PHPStan\Tests\ForbiddenClassRule\Fixtures;

use PHPUnit\Runner\AfterTestFailureHook;
use PHPUnit\Runner\BeforeFirstTestHook;

class ClassImplementsAllowedInterface implements AfterTestFailureHook
{
}
