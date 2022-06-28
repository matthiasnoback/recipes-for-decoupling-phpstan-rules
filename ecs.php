<?php

declare(strict_types=1);

use Symplify\CodingStandard\Fixer\Naming\StandardizeHereNowDocKeywordFixer;
use Symplify\EasyCodingStandard\Config\ECSConfig;
use Symplify\EasyCodingStandard\ValueObject\Set\SetList;

return static function (ECSConfig $config): void {
    $config->paths([__DIR__ . '/utils', __DIR__ . '/ecs.php']);

    $config->skip([StandardizeHereNowDocKeywordFixer::class, '*/Fixtures/*']);

    $config->sets([SetList::CONTROL_STRUCTURES, SetList::PSR_12, SetList::COMMON, SetList::SYMPLIFY]);
};
