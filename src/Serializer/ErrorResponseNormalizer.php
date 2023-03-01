<?php
# src/Serializer/ErrorResponseNormalizer.php
namespace App\Serializer;

use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ErrorResponseNormalizer implements NormalizerInterface
{
    /**
     * @throws ExceptionInterface
     */
    public function error(string $message,int $status = 400 ): JsonResponse
    {
        $flatten = new FlattenException();
        $flatten = $flatten->create(new \Exception($message, $status), $status, []);
        return new JsonResponse($this->normalize($flatten, 'json', ['groups' => 'main']), $status);
    }

    public function normalize($exception, string $format = null, array $context = []): array
    {
        return [
            'content' => 'Error',
            'exception'=> [
                'message' => $exception->getMessage(),
                'code' => $exception->getStatusCode(),
            ],
        ];
    }

    public function supportsNormalization($data, string $format = null, array $context = []): bool
    {
        return $data instanceof FlattenException;
    }
}