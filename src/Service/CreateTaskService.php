<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Task;
use App\Entity\User;
use App\Repository\TaskRepository;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class CreateTaskService
{
    public function __construct(
        private readonly TaskRepository $taskRepository,
        private readonly ValidatorInterface $validator
    ) {
    }

    public function createTask(User $user, $title, $description): void
    {
        $task = new Task($user, $title, $description);

        $errors = $this->validateTask($task);
        if (null !== $errors) {
            // TODO: create custom exception with errors in  the constructor
//            throw new ConstraintViolationException($errors);
            return;
        }

        $this->taskRepository->save($task);
    }

    private function validateTask(Task $task): ?ConstraintViolationList
    {
        $errors = $this->validator->validate($task);

        return 0 === \count($errors) ? null : $errors;
    }
}