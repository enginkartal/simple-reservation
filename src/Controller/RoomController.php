<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Entity\Room;
#[Route('/api/v1', name: 'api_')]
class RoomController extends AbstractController
{

    #[Route('/room', name: 'app_room')]
    public function index(ValidatorInterface $validator): JsonResponse
    {
        $room  = new Room();
        $errors = $validator->validate($room);

        if (count($errors) > 0) {
            return new JsonResponse((string) $errors, 400);
        }
        return $this->json([
            'message' => 'Welcome to your new controller!3',
            'path' => 'src/Controller/RoomController.php',
        ]);
    }
}
