<?php

declare(strict_types=1);

namespace App\Exceptions;

final class AccessDeniedException extends \Exception implements PublishedMessageException
{

}