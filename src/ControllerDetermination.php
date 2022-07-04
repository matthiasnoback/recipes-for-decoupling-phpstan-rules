<?php

declare(strict_types=1);

namespace Utils\PHPStan;

use PHPStan\Reflection\ClassReflection;

interface ControllerDetermination
{
    public function isController(ClassReflection $class): bool;
}
