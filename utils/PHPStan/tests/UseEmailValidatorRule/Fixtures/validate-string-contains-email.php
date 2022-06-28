<?php

declare(strict_types=1);

use Illuminate\Http\Request;

final class SomeController
{
    public function someAction(Request $request)
    {
        $request->validate(
            [
                'email_address' => 'required|email'
            ]
        );
    }
}
