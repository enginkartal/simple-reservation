<?php

namespace App\Controller;

use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

use App\Entity\Room;
use App\Repository\RoomRepository;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;
use App\Serializer\ErrorResponseNormalizer as ErrorNormalizer;
use App\Serializer\SuccessResponseNormalizer as SuccessNormalizer;


#[Route('/api/v1', name: 'api_')]
class RoomController extends AbstractController
{

    private ErrorNormalizer $errorNormalizer;
    private SuccessNormalizer $successNormalizer;

    public function __construct()
    {
        $this->errorNormalizer = new ErrorNormalizer();
        $this->successNormalizer = new SuccessNormalizer();
    }

    /**
     * List the rooms.
     *
     * This call takes avalaible rooms.
     * @throws ExceptionInterface
     */
    #[Route('/rooms', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Returns the rooms'
    )]
    #[OA\Parameter(
        name: 'check_in',
        description: 'The field used to check_in date',
        in: 'query',
        schema: new OA\Schema(type: 'date')
    )]
    #[OA\Parameter(
        name: 'check_out',
        description: 'The field used to check_out date',
        in: 'query',
        schema: new OA\Schema(type: 'date')
    )]
    #[OA\Parameter(
        name: 'guest',
        description: 'The field used to guest info',
        in: 'query',
        schema: new OA\Schema(type: 'integer')
    )]
    #[OA\Tag(name: 'rooms')]
    public function index(Request $request,ValidatorInterface $validator,RoomRepository $roomRepository ): JsonResponse
    {
        $room = new Room();

        $checkIn = $request->query->get('check_in');
        $checkOut = $request->query->get('check_out');
        $guest = $request->query->get('guest');

        if (empty($checkIn) || empty($checkOut) || empty($guest)) {
            return $this->errorNormalizer->error("Validation error", 400);
        }

        $room->setCheckIn($checkIn);
        $room->setCheckOut($checkOut);
        $room->setGuest($guest);

        $availableRooms = $roomRepository->findAvailableRooms($room);

        if (empty($availableRooms)) {
            return $this->errorNormalizer->error("No rooms available", 404);
        }

        return $this->successNormalizer->success($availableRooms, 200);
    }

}
