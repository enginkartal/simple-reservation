<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Repository\ReservationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
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

    #[Route('/reservations', name: 'reservations.gets', methods: ['GET'])]
    #[OA\Tag(name: 'reservations')]
    public function index(Request $request, ValidatorInterface $validator, ReservationRepository $reservationRepository): JsonResponse
    {

        $reservations = $reservationRepository->findAllReservations();

        if (empty($reservations)) {
            return $this->errorNormalizer->error("No reservations available", 404);
        }

        return $this->successNormalizer->success($reservations, 200);
    }

    #[Route('/reservations', name: 'reservations.post', methods: ['POST'])]
    #[OA\RequestBody(
        description: 'New Reservation',
        required: true,
        content: array(
            new OA\MediaType(
                mediaType: 'application/json',
                schema: new OA\Schema(
                    properties: array(
                        new OA\Property(
                            property: 'customer_id',
                            description: 'The customer ID',
                            type: 'integer'
                        ),
                        new OA\Property(
                            property: 'room_id',
                            description: 'The room ID',
                            type: 'integer'
                        ),
                        new OA\Property(
                            property: 'check_in',
                            description: 'The check_in date',
                            type: 'date'
                        ),
                        new OA\Property(
                            property: 'check_out',
                            description: 'The check_out date',
                            type: 'date'
                        ),
                        new OA\Property(
                            property: 'amount',
                            description: 'The amount',
                            type: 'integer'
                        ),
                    ),
                    type: 'object',
                )
            )
        ),
    )]
    #[OA\Tag(name: 'reservations')]
    public function create(Request $request, ValidatorInterface $validator, ReservationRepository $reservationRepository): JsonResponse
    {

        $reservation = new Reservation();
        $errors = $validator->validate($reservation);

        if (count($errors) > 0) {
            return $this->errorNormalizer->error((string)$errors, 400);
        }

        $data = json_decode($request->getContent(), true);
        $ref = 'REF_' . rand(1, 99999);
        $checkIn = new \DateTime($data['check_in']);
        $checkOut = new \DateTime($data['check_out']);

        $reservation->setRef($ref);
        $reservation->setCustomerId($data['customer_id']);
        $reservation->setRoomId($data['room_id']);
        $reservation->setCheckIn($checkIn);
        $reservation->setCheckOut($checkOut);
        $reservation->setAmount($data['amount']);
        $reservation->setCreatedAt(new \DateTime());

        $reservationRepository->save($reservation, true);

        return $this->successNormalizer->success(['ref' => $ref, 'message' => 'Successful created'], 201);
    }

    #[Route('/reservations/{ref}', name: 'reservations.get', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Return the reservation'
    )]
    #[OA\Tag(name: 'reservations')]
    public function show(Request $request, ValidatorInterface $validator, ReservationRepository $reservationRepository, string $ref): JsonResponse
    {
        $reservation = $reservationRepository->findReservationByRef($ref);
        if (empty($reservation)) {
            return $this->errorNormalizer->error("No reservation available", 404);
        }

        return $this->successNormalizer->success($reservation, 200);
    }

    /**
     * @throws ExceptionInterface
     */
    #[Route('/reservations/{ref}', name: 'reservations.delete', methods: ['DELETE'])]
    #[OA\Response(
        response: 200,
        description: 'Delete the reservation'
    )]
    #[OA\Tag(name: 'reservations')]
    public function delete(Request $request, ValidatorInterface $validator, ReservationRepository $reservationRepository, string $ref): JsonResponse
    {
        $reservation = $reservationRepository->getReservationByRef($ref);
        if (empty($reservation)) {
            return $this->errorNormalizer->error("No reservation available", 404);
        }

        $reservationRepository->remove($reservation, true);

        return $this->successNormalizer->success(['message' => 'Successful deleted'], 200);
    }

}
