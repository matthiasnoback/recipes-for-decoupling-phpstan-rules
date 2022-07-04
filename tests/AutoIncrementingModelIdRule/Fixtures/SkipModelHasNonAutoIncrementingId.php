<?php

declare(strict_types=1);

namespace Utils\PHPStan\Tests\ForbiddenOverrideRule\Fixtures;

use Illuminate\Database\Eloquent\Model;

final class SkipModelHasNonAutoIncrementingId extends Model
{
    public $incrementing = false;
}
