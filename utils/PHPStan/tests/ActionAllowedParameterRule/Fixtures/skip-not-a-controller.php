<?php

declare(strict_types=1);

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SomeOtherTypeOfClass
{
    public function someAction(
        Request $request,
    ): Response {
    }

    public function someOtherAction(
        Request $request,
    ): Response {
    }
}
