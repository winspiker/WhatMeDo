<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\User;
use App\Repository\TaskRepository;

final class TaskService
{
    public function __construct(
        private readonly TaskRepository $taskRepository,
    ) {

    }

    public function getByUser(User $user): array
    {
        if (in_array("ROLE_ADMIN", $user->getRoles(), true)) {
            return $this->taskRepository->findAll();
        }

        return $user->getTasks()->toArray();
    }
}