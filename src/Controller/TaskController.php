<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Task;
use App\Entity\User;
use App\Factory\TaskFactory;
use App\Repository\TaskRepository;
use App\Service\CreateTaskService;
use App\Service\TaskService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Twig\Environment;

class TaskController
{
    public function __construct(
        private readonly CreateTaskService $createTaskService,
        private readonly ValidatorInterface $validator,
        private readonly Environment $renderer,
        private readonly Security $security,
        private readonly Router $router,
    ) {
    }

    public function deleteTask(EntityManagerInterface $entityManager, Task $task): Response
    {
        $entityManager->remove($task);
        $entityManager->flush();

        return new RedirectResponse($this->router->generate("main"));
    }

    public function doneTask(EntityManagerInterface $entityManager, Task $task): Response
    {
        $task->done();
        $entityManager->flush();

        return new RedirectResponse($this->router->generate("main"));
    }

    public function createTask(Request $request): Response
    {
        $description = $request->request->get('description');
        $title = $request->request->get('title');

        /** @var User $user */
        $user = $this->security->getUser();

//        try {
            $this->createTaskService->createTask($user, $title, $description);
//        } catch (ConstraintViolationException $ex) {
//            TODO: set collection errros to the session & redirect user to main page
//            return new RedirectResponse($this->router->generate('main'));
//        }

        return new RedirectResponse($this->router->generate( "main"));
    }

    public function fakeCreateTask(int $number, EntityManagerInterface $entityManager): Response
    {
        $number = (int) $number;
        if (!in_array($number, [1, 20], true)) {
            $repo = $entityManager->getRepository(Task::class);
            $tasks = $repo->findAll();
            return $this->render("todo/todo.html.twig", ['error' => 'Invalid number to create fake tasks', 'tasks' => $tasks]);
        }

        TaskFactory::createMany($number);
        return $this->redirectToRoute("main");
    }

    public function edit(Task $task): Response
    {
        return $this->render('todo/edit.html.twig', ['task' => $task]);
    }

    public function updated(EntityManagerInterface $entityManager, Task $task, Request $request, ValidatorInterface $validator): Response
    {
        $taskUpd = $this->updateTask($task, $request, $validator);

        if ($taskUpd instanceof ConstraintViolationList) {
            $errors = $taskUpd->get(0)->getMessage();
            return $this->render('todo/edit.html.twig', ['error' => $errors, 'task' => $task]);
        }

        $entityManager->flush();
        return $this->redirectToRoute("main");
    }

    private function updateTask(Task $task, Request $request, ValidatorInterface $validator): Task|ConstraintViolationList
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