<?php

declare(strict_types=1);

namespace Utils\PHPStan\Tests\InputOutputOnlyInCommandRule\Fixtures;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;

class AnActualCommand extends Command
{
    public function foo(InputInterface $input)
    {
        $input->getArgument('arg');
    }
}
