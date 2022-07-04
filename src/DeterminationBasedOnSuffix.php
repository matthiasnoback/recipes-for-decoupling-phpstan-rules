<?php

declare(strict_types=1);

namespace Utils\PHPStan;

use PHPStan\Reflection\ClassReflection;

final class DeterminationBasedOnSuffix implements ControllerDetermination
{
    public function __construct(
        private readonly string $suffix = 'Controller'
    ) {
    }

    public function isController(ClassReflection $class): bool
    {
        return str_ends_with($class->getName(), $this->suffix);
    }
}
