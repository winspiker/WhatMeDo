<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Component\HttpFoundation\Request;

class SessionErrorHandlingService
{
    public static function hasErrorSession(Request $request): string
    {
        $session = $request->getSession();

        if (!$session->has('error')) {
            return '';
        }

        $error = $session->get('error')['message'];
        $session->remove('error');

        return $error;

    }
}