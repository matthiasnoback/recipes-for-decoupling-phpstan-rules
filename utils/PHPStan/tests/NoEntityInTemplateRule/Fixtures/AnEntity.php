<?php

declare(strict_types=1);

namespace Utils\PHPStan\Tests\NoEntityInTemplateRule\Fixtures;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
final class AnEntity
{
}
