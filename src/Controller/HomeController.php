<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Task;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class HomeController extends AbstractController
{


#-----------------------------------------------------------------#

    ####################### HOME ROUTE #######################
    public function home(EntityManagerInterface $entityManager): Response
    {

        $repo = $entityManager->getRepository(Task::class);
        $tasks = $repo->findAll();

        return $this->render('todo/todo.html.twig', ['tasks' => $tasks]);

    }
    ####################### END HOME ROUTE #######################

#-----------------------------------------------------------------#


    ####################### DELETE ROUTE #######################
    public function deleteTask(EntityManagerInterface $entityManager, Task $task): Response
    {

        $entityManager->remove($task);
        $entityManager->flush();

        return $this->redirectToRoute("todo_list");
    }
    ####################### END DELETE ROUTE #######################

#-----------------------------------------------------------------#


    ####################### DONE ROUTE #######################
    public function doneTask(EntityManagerInterface $entityManager, Task $task): Response
    {

        $task->doneStatus();
        $entityManager->flush();

        return $this->redirectToRoute("todo_list");
    }
    ####################### END DONE ROUTE #######################

#-----------------------------------------------------------------#

    ####################### CREATE ROUTE #######################
    public function createTask(EntityManagerInterface $entityManager, Request $request): Response
    {
        $title = $request->request->get('title');
        $create = $request->request->get('create');
        $description = $request->request->get('description');

        if (!isset($create) || $title === '') {
            return $this->redirectToRoute("todo_list");
        }

        $task = new Task();
        $task->setTitle($title);
        $task->setDescription($description ?? null);

        $entityManager->persist($task);
        $entityManager->flush();

        return $this->redirectToRoute("todo_list");
    }

    ####################### END CREATE ROUTE #######################

#-----------------------------------------------------------------#

    ####################### EDIT ROUTE #######################
    public function edit(Task $task)
    {
        return $this->render('todo/edit.html.twig', ['task' => $task]);
    }
    ####################### END EDIT ROUTE #######################

#-----------------------------------------------------------------#

    ####################### UPDATE ROUTE #######################
    public function updated(EntityManagerInterface $entityManager, Task $task, Request $request, ValidatorInterface $validator)
    {

        $taskUpd = $this->updateTask($task, $request, $validator);

        if ($taskUpd instanceof ConstraintViolationList) {
            $error = $taskUpd->get(0)->getMessage();
            return $this->render('todo/edit.html.twig', ['error' => $error, 'task' => $task]);
        }

        $entityManager->flush();
        return $this->redirectToRoute("todo_list");
    }


    private function updateTask(Task $task, Request $request, ValidatorInterface $validator)
    {
        $title = $request->request->get('title');
        $update = $request->request->get('update');
        $description = $request->request->get('description');

        $task->setTitle($title);
        $task->setDescription($description ?? null);

        $errors = $validator->validate($task);
        if((bool)$errors->count()) {
            return $errors;
        }

        $task->setUpdated();
        return $task;
    }

    ####################### END UPDATE ROUTE #######################



}