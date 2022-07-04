<?php

declare(strict_types=1);

namespace Utils\PHPStan;

use PHPStan\Reflection\ClassReflection;

final class DoctrineEntityWithAnnotation implements EntityDetermination
{
    public function isEntity(ClassReflection $class): bool
    {
        return str_contains($class->getResolvedPhpDoc() ->getPhpDocString(), '@ORM\Entity',);
    }
}
