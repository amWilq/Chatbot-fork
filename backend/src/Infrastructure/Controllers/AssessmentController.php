<?php

namespace App\Infrastructure\Controllers;

use App\Application\Services\AssessmentService;
use App\Application\Services\AssessmentTypeService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

class AssessmentController extends AbstractBaseController
{
    public function __construct(
        private readonly AssessmentService $assessmentService,
        private readonly AssessmentTypeService $assessmentTypeService,
        private readonly JsonEncoder $jsonEncoder,
    ) {
    }

    #[Route('/assessments/types', name: 'app.assessments.types.all', methods: ['GET'])]
    public function getAllAssessmentTypes(): JsonResponse
    {
        $output = $this->assessmentTypeService->getAllAssessmentTypes();

        return $this->prettyJsonResponse($output);
    }

    #[Route('/assessments/types/{id}', name: 'app.assessments.types.single', methods: ['GET'])]
    public function getSingleAssessmentTypeById(string $id): JsonResponse
    {
        $output = $this->assessmentTypeService->getAssessmentTypeById($id);

        if (!$output) {
            return new JsonResponse("Assessment Type with id: `$id` was not found", 400);
        }

        return $this->prettyJsonResponse($output);
    }

    #[Route('/assessments/{assessmentTypeName}/start', name: 'app.assessments.start', methods: ['POST'])]
    public function startAssessment(Request $request, string $assessmentTypeName): JsonResponse
    {
        $output[] =
            $this->assessmentService->startAssessment(
                $this->jsonEncoder->decode($request->getContent(), JsonEncoder::FORMAT, ['json_decode_associative' => false])
            );

        return $this->prettyJsonResponse($output);
    }

    #[Route('/assessments/{assessmentTypeName}/{assessmentId}', name: 'app.assessments.interact', methods: ['POST'])]
    public function assessmentInteraction(string $assessmentTypeName, string $assessmentId): JsonResponse
    {
        $output = [];

        return $this->prettyJsonResponse($output);
    }

    #[Route('/assessments/{assessmentTypeName}/{assessmentId}/complete', name: 'app.assessments.complete', methods: ['POST'])]
    public function completeAssessment(string $assessmentTypeName, string $assessmentId): JsonResponse
    {
        $output = [];

        return $this->prettyJsonResponse($output);
    }
}
