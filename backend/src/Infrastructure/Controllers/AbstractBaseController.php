<?php

namespace App\Infrastructure\Controllers;

use App\Port\Inbound\InboundPortInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

abstract class AbstractBaseController extends AbstractController implements InboundPortInterface
{
    protected function prettyJsonResponse(array $output): JsonResponse
    {
        $response = new JsonResponse($output, 200);
        $response->setEncodingOptions($response->getEncodingOptions() | JSON_PRETTY_PRINT);

        return $response;
    }
}
