<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\ContainerInterface;

final class SomeController
{
    private ContainerInterface $container;

    public function someMethod(): void
    {
        $this->container->get('logger');
    }
}
