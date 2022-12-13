<?php

declare(strict_types=1);

namespace App\Exceptions;

final class InvalidFakeNumber extends \Exception implements PublishedMessageException
{
}