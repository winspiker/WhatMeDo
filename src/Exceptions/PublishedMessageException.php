<?php

declare(strict_types=1);

namespace App\Exceptions;

interface PublishedMessageException
{
    public function getMessage(): string;
}