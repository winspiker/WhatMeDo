<?php

namespace App\Controller;

use App\Entity\Task;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends AbstractController
{
    public function home(EntityManagerInterface $entityManager, Request $request): Response
    {

        $repo = $entityManager->getRepository(Task::class);
        $this->addTask($entityManager, $request);
        $this->deleteTask($entityManager, $repo, $request);
        $this->doneTask($repo, $request);
        $entityManager->flush();

        $tasks = $repo->findAll();
        return $this->render('todo/todo.html.twig', ['tasks' => $tasks]);
    }

    private function deleteTask(EntityManagerInterface $entityManager, $repo, Request $request): void
    {
        $delete = $request->request->get('delete');

        if (!$delete) {
            return;
        }

        $task = $repo->find($delete);
        if (isset($task)) {
            $entityManager->remove($task);
        }
    }

    private function doneTask($repo, Request $request): void
    {
        $done = $request->request->get('done');

        if (!$done) {
            return;
        }

        $task = $repo->find($done);
        $task?->doneStatus();
    }

    public function addTask(EntityManagerInterface $entityManager, Request $request): void
    {
        $title = $request->request->get('title');
        $create = $request->request->get('create');
        $description = $request->request->get('description');

        if (!isset($create) || $title === '') {
            return;
        }

        $task = new Task();
        $task->setTitle($title);
        $task->setDescription($description ?? null);

        $entityManager->persist($task);
    }


}