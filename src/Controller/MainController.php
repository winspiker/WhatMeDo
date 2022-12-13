<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Service\SessionErrorHandlingService;
use App\Service\TaskService;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

final class MainController
{
    public function __construct(
        private readonly TaskService $taskService,
        private readonly Environment $renderer,
        private readonly Security $security,
    ) {

    }

    /**
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
     */
    public function home(Request $request): Response
    {
        /** @var User $user */
        $user = $this->security->getUser();
        $tasks = $this->taskService->getByUser($user);

        $errorMessage = SessionErrorHandlingService::hasErrorSession($request);

        return new Response($this->renderer->render('todo/todo.html.twig', ['tasks' => $tasks, 'error' => $errorMessage]));
    }







}