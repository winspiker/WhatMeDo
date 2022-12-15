<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Task;
use App\Entity\User;
use App\Exceptions\ConstraintViolationException;
use App\Repository\TaskRepository;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use function count;

final class CreateTaskService
{
    public function __construct(
        private readonly TaskRepository $taskRepository,
        private readonly ValidatorInterface $validator
    ) {
    }

    /**
     * @throws ConstraintViolationException
     */
    public function createTask(User $user, string $title, ?string $description): void
    {
        $task = new Task($user ,$title, $description);

        $errors = $this->validateTask($task);
        if (null !== $errors) {
            $errorMessage = $errors->get(0)->getMessage();
            throw new ConstraintViolationException($errorMessage);
        }

        $this->taskRepository->save($task);
    }

    private function validateTask(Task $task): ?ConstraintViolationList
    {
        $errors = $this->validator->validate($task);

        return 0 === count($errors) ? null : $errors;
    }
}