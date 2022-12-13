<?php

declare(strict_types=1);

namespace App\Exceptions;

class InvalidFakeNumber extends \Exception implements PublishedMessageException
{
}