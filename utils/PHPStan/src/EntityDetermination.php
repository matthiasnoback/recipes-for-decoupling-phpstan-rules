<?php

declare(strict_types=1);

namespace Utils\PHPStan;

use PHPStan\Reflection\ClassReflection;

interface EntityDetermination
{
    public function isEntity(ClassReflection $class): bool;
}
