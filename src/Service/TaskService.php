<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\User;
use App\Repository\TaskRepository;
use Symfony\Bundle\SecurityBundle\Security;

final class TaskService
{
    public function __construct(
        private readonly Security $security,
        private readonly TaskRepository $taskRepository,
    ) {
    }

    public function getTasksByAuthenticatedUser(): array
    {
        /** @var User $user */
        $user = $this->security->getUser();

        if ($user === null) {
            throw new \RuntimeException('User is not authenticated.');
        }

        if (in_array("ROLE_ADMIN", $user->getRoles(), true)) {
            return $this->taskRepository->findAll();
        }

        return $user->getTasks()->toArray();
    }
}