<?php

namespace App\Infrastructure\Controllers;

use App\Application\Services\AssessmentServiceInterface;
use App\Application\Services\AssessmentTypeServiceInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
#[Route('/assessments', name: 'app.assessments.')]
class AssessmentController extends AbstractBaseController
{
    public function __construct(
        private readonly AssessmentServiceInterface $assessmentService,
        private readonly AssessmentTypeServiceInterface $assessmentTypeService,
        private readonly JsonEncoder $jsonEncoder,
    ) {
    }

    #[Route('/types', name: 'types.all', methods: ['GET'])]
    public function getAllAssessmentTypes(): JsonResponse
    {
        $output = $this->assessmentTypeService->getAllAssessmentTypes();

        return $this->prettyJsonResponse($output);
    }

    #[Route('/types/{id}', name: 'types.single', methods: ['GET'])]
    public function getSingleAssessmentTypeById(string $id): JsonResponse
    {
        $output = $this->assessmentTypeService->getAssessmentTypeById($id);

        if (!$output) {
            return new JsonResponse("Assessment Type with id: `$id` was not found", 400);
        }

        return $this->prettyJsonResponse($output);
    }

    #[Route('/{assessmentTypeName}/start', name: 'start', methods: ['POST', 'OPTIONS'])]
    public function startAssessment(Request $request, string $assessmentTypeName): JsonResponse
    {
        $output = $this->assessmentService->startAssessment(
            $this->jsonEncoder->decode(
                $request->getContent(), JsonEncoder::FORMAT, ['json_decode_associative' => false]
            ),
            $assessmentTypeName
        );

        return $this->prettyJsonResponse($output);
    }

    #[Route('/{assessmentTypeName}/{assessmentId}/complete', name: 'complete', methods: ['POST', 'OPTIONS'])]
    public function completeAssessment(Request $request, string $assessmentTypeName, string $assessmentId): JsonResponse
    {
        $output = $this->assessmentService->completeAssessment(
            $this->jsonEncoder->decode(
                $request->getContent(), JsonEncoder::FORMAT, ['json_decode_associative' => false]
            ),
            [$assessmentTypeName, $assessmentId]
        );

        return $this->prettyJsonResponse($output);
    }
}
