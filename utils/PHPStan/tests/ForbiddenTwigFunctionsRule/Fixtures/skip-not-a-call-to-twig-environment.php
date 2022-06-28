<?php

declare(strict_types=1);

class Foo
{
    private NotTwigEnvironment $twig;

    public function foo(): void {
        $this->twig->render(
            'uses-forbidden-var.html.twig',
        );
    }
}
