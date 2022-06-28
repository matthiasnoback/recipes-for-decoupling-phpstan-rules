<?php

declare(strict_types=1);

namespace Utils\PHPStan\Tests\ForbiddenObjectTypeInCommandRule;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use Symfony\Component\Console\Input\InputInterface;
use Utils\PHPStan\ForbiddenObjectTypeInCommandRule;

final class ForbiddenObjectTypeInCommandRuleTest extends RuleTestCase
{
    public function testRule(): void
    {
        $this->analyse(
            [__DIR__ . '/Fixtures/NotACommand.php'],
            [
                [
                    'Object of type Symfony\Component\Console\Input\InputInterface is used in a class that does not extend Symfony\Component\Console\Command\Command',
                    13,
                ],
            ]
        );
    }

    public function testSkipInputOutputUsedInCommand(): void
    {
        $this->analyse([__DIR__ . '/Fixtures/AnActualCommand.php'], []);
    }

    protected function getRule(): Rule
    {
        return new ForbiddenObjectTypeInCommandRule(InputInterface::class);
    }
}
