<?php

declare(strict_types=1);

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SomeController
{
    public function actionReturnsResponse(
        Request $request,
    ): Response {
    }
}
