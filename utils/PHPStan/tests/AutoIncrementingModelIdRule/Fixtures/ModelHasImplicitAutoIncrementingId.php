<?php

declare(strict_types=1);

namespace Utils\PHPStan\Tests\ForbiddenOverrideRule\Fixtures;

use Illuminate\Database\Eloquent\Model;

final class ModelHasImplicitAutoIncrementingId extends Model
{
}
