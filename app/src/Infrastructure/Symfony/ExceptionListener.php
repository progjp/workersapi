<?php

namespace App\Infrastructure\Symfony;

use App\Util\Exception\DetailedException;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

#[AsEventListener(event: KernelEvents::EXCEPTION)]
class ExceptionListener
{
    public function __invoke(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        $message = [
            'name' => (new \ReflectionClass($exception))->getShortName(),
            "details" => [
                [
                    'issue' => $exception->getMessage(),
                ]
            ]
        ];

        if ($exception instanceof DetailedException) {
            $message = [
                'name' => (new \ReflectionClass($exception))->getShortName(),
            ];
            foreach ($exception->details as $detail) {
                $message['details'][] = [
                    'issue' => $detail->getIssue(),
                    'code' => $detail->getCode(),
                ];
            }
        }

        $response = new JsonResponse($message);
        $response->setStatusCode(Response::HTTP_BAD_REQUEST);

        $event->setResponse($response);
    }
}
