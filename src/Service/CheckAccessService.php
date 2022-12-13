<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Task;
use App\Entity\User;
use App\Exceptions\AccessDeniedException;

final class CheckAccessService
{
    /**
     * @throws AccessDeniedException
     */
    public static function hasAccess(User $user, Task $task): void
    {

        if ($user->getRoles() === ['ROLE_ADMIN']) {
            return;
        }

        if ($task->getCreator() !== $user) {
            throw new AccessDeniedException('Access denied! Only creator/admin can do this with this task!');
        }
    }

}