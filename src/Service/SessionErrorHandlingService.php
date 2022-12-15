<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Component\HttpFoundation\Request;

final class SessionErrorHandlingService
{
    public static function hasErrorSession(Request $request): array
    {
        $session = $request->getSession();

        if (!$session->has('error')) {
            return [];
        }

        $error = $session->get('error');
        $session->remove('error');

        return $error;

    }
}