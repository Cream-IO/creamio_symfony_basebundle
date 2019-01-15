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
use Ramsey\Uuid\Uuid;

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
        $uniqueLogId = Uuid::uuid4();

        if ($e instanceof APIException) {
            $APIError = $e->getAPIError();
        } else {
            $statusCode = $e instanceof HttpExceptionInterface ? $e->getStatusCode() : 500;
            $APIError = new APIError(
                $statusCode,
                $e->getMessage()
            );
        }

        $arrayToDisplay = $APIError->toArray();
        $arrayToDisplay['additional-informations']['unique-log-id'] = $uniqueLogId;
        $this->logger->error($APIError->getType(), $arrayToDisplay);
        unset($arrayToDisplay['additional-informations']['technical']);

        // Handle system errors and display generic message.
        if (500 === $APIError->getStatusCode()) {
            $errorMessage = [
                'status' => 'error',
                'code' => 500,
                'type' => 'Internal Server Error',
                'reason' => 'An error occured, please contact us with id in additionnal informations.',
                'additional-informations' => [
                    'unique-log-id' => $uniqueLogId,
                ],
            ];
            $response = new JsonResponse(
                $errorMessage,
                $APIError->getStatusCode()
            );
        // Handle errors to display correctly to the user.
        } else {
            $response = new JsonResponse(
                $arrayToDisplay,
                $APIError->getStatusCode()
            );
        }
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
