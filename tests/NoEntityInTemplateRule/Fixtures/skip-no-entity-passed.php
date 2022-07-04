<?php

declare(strict_types=1);

use Twig\Environment;

class Foo
{
    private Environment $twig;

    public function foo(): void {
        $this->twig->render(
            'template.html.twig',
            [
                'notAnEntity' => new DateTimeImmutable(),
            ]
        );
    }
}
