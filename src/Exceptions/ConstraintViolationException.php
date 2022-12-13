<?php

declare(strict_types=1);

namespace App\Exceptions;

class ConstraintViolationException extends \Exception implements PublishedMessageException
{

}