<?php

namespace CreamIO\BaseBundle\EventSubscriber;

use CreamIO\BaseBundle\Exceptions\APIError;
use CreamIO\BaseBundle\Exceptions\APIException;
use CreamIO\BaseBundle\Service\LoggerProvider;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Simple event subscriber that converts exceptions to JSON format.
 */
class APIExceptionSubscriber implements EventSubscriberInterface
{
    /**
     * @var \Symfony\Bridge\Monolog\Logger Injected logger
     */
    private $logger;

    /**
     * APIExceptionSubscriber constructor.
     *
     * @param LoggerProvider $loggerProvider Injected logger provider
     */
    public function __construct(LoggerProvider $loggerProvider)
    {
        $this->logger = $loggerProvider->logger();
    }

    /**
     * Actually converts the exception to JSON format and sets the response accordingly.
     *
     * @param GetResponseForExceptionEvent $event
     */
    public function onKernelException(GetResponseForExceptionEvent $event): void
    {
        $e = $event->getException();
        if ($e instanceof APIException) {
            $APIError = $e->getAPIError();
        } else {
            $statusCode = $e instanceof HttpExceptionInterface ? $e->getStatusCode() : 500;
            $APIError = new APIError(
                $statusCode,
                $e->getMessage()
            );
        }
        $this->logger->error(sprintf($APIError->getType(), $APIError->getStatusCode()), $APIError->toArray());
        $arrayToDisplay = $APIError->toArray();
        unset($arrayToDisplay['additional-informations']['technical']);
        $response = new JsonResponse(
            $APIError->toArray(),
            $APIError->getStatusCode()
        );
        $response->headers->set('Content-Type', 'application/problem+json');
        $event->setResponse($response);
    }

    /**
     * Kernel function to specify the event we listen to.
     *
     * @codeCoverageIgnore
     */
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException',
        ];
    }
}
