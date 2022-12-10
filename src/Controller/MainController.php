<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\TaskService;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

final class MainController
{
    public function __construct(
        private readonly TaskService $taskService,
        private readonly Environment $renderer,
    ) {
    }

    public function home(): Response
    {
        $tasks = $this->taskService->getTasksByAuthenticatedUser();

        return new Response($this->renderer->render('todo/todo.html.twig', ['tasks' => $tasks]));
    }
}