<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Task;
use App\Entity\User;
use App\Exceptions\AccessDeniedException;
use App\Exceptions\ConstraintViolationException;
use App\Exceptions\InvalidFakeNumber;
use App\Factory\TaskFactory;
use App\Repository\TaskRepository;
use App\Service\CheckAccessService;
use App\Service\CreateTaskService;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

final class TaskController
{
    public function __construct(
        private readonly CreateTaskService  $createTaskService,
        private readonly TaskRepository     $taskRepository,
        private readonly ValidatorInterface $validator,
        private readonly RouterInterface    $router,
        private readonly Environment        $renderer,
        private readonly Security           $security,
    ) {

    }

    /**
     * @throws AccessDeniedException
     */
    public function deleteTask(Task $task): Response
    {

        /** @var User $user */
        $user = $this->security->getUser();
        CheckAccessService::hasAccess($user, $task);

        $this->taskRepository->remove($task);
        return new RedirectResponse($this->router->generate("main"));
    }

    /**
     * @throws AccessDeniedException
     */
    public function doneTask(Task $task): Response
    {
        /** @var User $user */
        $user = $this->security->getUser();
        CheckAccessService::hasAccess($user, $task);

        $task->done();
        $this->taskRepository->save($task);

        return new RedirectResponse($this->router->generate("main"));
    }

    /**
     * @throws ConstraintViolationException
     */
    public function createTask(Request $request): Response
    {

        $description = $request->request->get('description');
        $title = $request->request->get('title');

        /** @var User $user */
        $user = $this->security->getUser();

        $this->createTaskService->createTask($user, $title, $description);

        return new RedirectResponse($this->router->generate("main"));
    }


    /**
     * @throws InvalidFakeNumber
     */
    public function fakeCreateTask(int $number): Response
    {
        if (!in_array($number, [1, 20], true)) {
            throw new InvalidFakeNumber('Invalid number to create fake tasks');
        }

        TaskFactory::createMany($number);
        return new RedirectResponse($this->router->generate("main"));
    }


    /**
     * @throws SyntaxError
     * @throws AccessDeniedException
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function edit(Task $task): Response
    {
        /** @var User $user */
        $user = $this->security->getUser();
        CheckAccessService::hasAccess($user, $task);

        return new Response($this->renderer->render('todo/edit.html.twig', ['task' => $task]));
    }

    /**
     * @throws ConstraintViolationException
     * @throws AccessDeniedException
     */
    public function update(Task $task, Request $request): Response
    {
        /** @var User $user */
        $user = $this->security->getUser();
        CheckAccessService::hasAccess($user, $task);

        $updateResult = $this->updateTask($task, $request);
        if ($updateResult instanceof ConstraintViolationList) {
            $error = $updateResult->get(0)->getMessage();
            throw new ConstraintViolationException($error);
        }

        $this->taskRepository->save($updateResult);
        return new RedirectResponse($this->router->generate("main"));
    }

    private function updateTask(Task $task, Request $request): Task|ConstraintViolationList
    {

        $title = $request->request->get('title');
        $description = $request->request->get('description');

        try {
            $task->changeTitle($title);
            $task->changeDescription($description ?? null);
        } catch (\InvalidArgumentException) {
            return $this->validator->validate($task);
        }

        return $task;
    }
}