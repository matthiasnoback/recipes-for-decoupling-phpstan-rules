<?php

declare(strict_types=1);

use Twig\Environment;
use Utils\PHPStan\Tests\NoEntityInTemplateRule\Fixtures\AnEntity;

class Foo
{
    private Environment $twig;

    public function foo(): void {
        $this->twig->render(
            'template.html.twig',
            [
                'anEntity' => new AnEntity(),
            ]
        );
    }
}
