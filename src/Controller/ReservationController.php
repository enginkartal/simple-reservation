<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Repository\ReservationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;
use App\Serializer\ErrorResponseNormalizer as ErrorNormalizer;
use App\Serializer\SuccessResponseNormalizer as SuccessNormalizer;

#[Route('/api/v1', name: 'api_')]
class ReservationController extends AbstractController
{
    private ErrorNormalizer $errorNormalizer;
    private SuccessNormalizer $successNormalizer;

    public function __construct()
    {
        $this->errorNormalizer = new ErrorNormalizer();
        $this->successNormalizer = new SuccessNormalizer();
    }

    #[Route('/reservations', name: 'reservations', methods: ['GET'])]
    #[OA\Tag(name: 'reservations')]
    public function index(Request $request,ValidatorInterface $validator,ReservationRepository $reservationRepository ): JsonResponse
    {

        $reservations = $reservationRepository->findAllReservations();

        if (empty($reservations)) {
            return $this->errorNormalizer->error("No reservations available", 404);
        }

        return $this->successNormalizer->success($reservations, 200);
    }
}
