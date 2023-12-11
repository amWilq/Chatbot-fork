<?php

namespace App\Infrastructure\Controllers;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class AbstractBaseController extends AbstractController
{
    protected function prettyJsonResponse(array $output): JsonResponse
    {
        $response = new JsonResponse($output, 200);
        $response->setEncodingOptions($response->getEncodingOptions() | JSON_PRETTY_PRINT);

        return $response;
    }
}
