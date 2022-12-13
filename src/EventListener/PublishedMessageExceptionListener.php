<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Exceptions\PublishedMessageException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\Routing\RouterInterface;

class PublishedMessageExceptionListener
{
    public function __construct(
        private readonly RouterInterface $router,
    ) {
    }
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        if (!$exception instanceof PublishedMessageException) {
            return;
        }

        $errorData = [
            'message' => $exception->getMessage()
        ];

        $event->getRequest()->getSession()->set('error', $errorData);

        $event->setResponse(new RedirectResponse($this->router->generate('main')));
    }
}