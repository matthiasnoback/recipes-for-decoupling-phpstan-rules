<?php

declare(strict_types=1);

use Symfony\Component\HttpFoundation\Request;

class SomeController
{
    private function notaPublicMethod(
        Request $request,
    ): array {
    }
}
