<?php

declare(strict_types=1);
namespace App\Value;

enum ProgressValue: string {
    case InProgress = 'in_progress';
    case Done = 'done';
}

