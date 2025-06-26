<?php

namespace App\EventSubscribers;

use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;

class ExceptionSubscriber implements EventSubscriberInterface
{

    private KernelInterface $kernel;

    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException',
        ];
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if ($exception instanceof ValidationFailedException) {
            $this->handleValidationExceptions($event);
            return;
        }

        $response = [
            'error' => [
                'message' => $exception->getMessage(),
                'code' => $exception->getCode()
            ],
            'status' => 'error',
        ];

        if ($this->kernel->getEnvironment() == 'dev') $response['error']['track'] = $exception->getTrace();

        $response = new JsonResponse($response);
        if ($exception instanceof HttpExceptionInterface) $response->setStatusCode($exception->getStatusCode());
        else $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);

        $event->setResponse($response);
    }

    public function handleValidationExceptions(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        $errors = [];
        foreach ($exception->getViolations() as $violation) {
            $errors[$violation->getPropertyPath()] = $violation->getMessage();
        }

        $response = new JsonResponse([
            'status' => 'validation_error',
            'errors' => $errors,
        ], Response::HTTP_UNPROCESSABLE_ENTITY);

        $event->setResponse($response);
    }
}