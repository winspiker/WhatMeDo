<?php

declare(strict_types=1);

namespace App\Exceptions;

final class ConstraintViolationException extends \Exception implements PublishedMessageException
{

}