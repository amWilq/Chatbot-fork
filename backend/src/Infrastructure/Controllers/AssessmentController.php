<?php

namespace App\Infrastructure\Controllers;

use App\Application\Services\AssessmentService;
use App\Application\Services\AssessmentTypeService;
use App\Application\Services\WebsocketService;
use App\Domain\Assessment\Enums\AssessmentStatusEnum;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

class AssessmentController extends AbstractBaseController
{
    public function __construct(
        private readonly AssessmentService $assessmentService,
        private readonly AssessmentTypeService $assessmentTypeService,
        private readonly WebsocketService $websocketService,
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
        $output = $this->assessmentService->startAssessment(
            $this->jsonEncoder->decode(
                $request->getContent(), JsonEncoder::FORMAT, ['json_decode_associative' => false]
            ),
            $assessmentTypeName
        );
        //TODO: for now not being updated, need to make changes in persistence layer
//        $assessment = $this->assessmentService->getAssessment();
//        $assessment->setStatus(AssessmentStatusEnum::ASSESSMENT_IN_PROGRESS);
//        $this->assessmentService->setAssessment($assessment);

//        $this->websocketService->connect(
//            $this->assessmentService->getAssessment()
//                ->getUser()->getId()->toString()
//        );

        return $this->prettyJsonResponse($output);
    }

    #[Route('/assessments/{assessmentTypeName}/{assessmentId}', name: 'app.assessments.interact', methods: ['POST'])]
    public function assessmentInteraction(Request $request, string $assessmentTypeName, string $assessmentId): JsonResponse
    {
        $output = [];

        return $this->prettyJsonResponse($output);
    }

    #[Route('/assessments/{assessmentTypeName}/{assessmentId}/complete', name: 'app.assessments.complete', methods: ['POST'])]
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
