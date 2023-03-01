<?php
# src/Serializer/SuccessResponseNormalizer.php
namespace App\Serializer;


use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class SuccessResponseNormalizer implements NormalizerInterface
{

    public function success(array $data,int $status = 200 ): JsonResponse
    {
        return new JsonResponse($this->normalize($data, 'json', ['groups' => 'main']), $status);
    }

    public function normalize($data, string $format = null, array $context = []): array
    {
        return [
            'content' => 'Success',
            'data'=> $data,
        ];
    }

    public function supportsNormalization($data, string $format = null, array $context = []): bool
    {
        return $data instanceof Response;
    }
}