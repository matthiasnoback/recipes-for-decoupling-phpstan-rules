<?php

declare(strict_types=1);

use Utils\PHPStan\Tests\ModelCreatedWithArrayRule\Fixtures\NotAModel;

$validatedData = [];

NotAModel::create($validatedData);
