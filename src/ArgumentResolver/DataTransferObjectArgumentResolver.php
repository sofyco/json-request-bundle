<?php declare(strict_types=1);

namespace Sofyco\Bundle\JsonRequestBundle\ArgumentResolver;

use Sofyco\Bundle\JsonRequestBundle\Attribute\DTO;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;

final readonly class DataTransferObjectArgumentResolver implements ValueResolverInterface
{
    public function __construct(private SerializerInterface $serializer)
    {
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        if (0 === \count($argument->getAttributes(DTO::class))) {
            return;
        }

        try {
            yield from $this->createObject($request, $argument);
        } catch (\Throwable $exception) {
            throw new BadRequestHttpException($exception->getMessage());
        }
    }

    private function getRequestData(Request $request): array
    {
        return \array_merge(
            (array) $request->attributes->get('_route_params', []),
            $request->files->all(),
            $request->query->all(),
            $request->request->all(),
        );
    }

    private function createObject(Request $request, ArgumentMetadata $argument): \Generator
    {
        $data = $this->getRequestData($request);

        if ($this->serializer instanceof DenormalizerInterface && null !== $argument->getType()) {
            yield $this->serializer->denormalize(
                data: $data,
                type: $argument->getType(),
                context: [AbstractObjectNormalizer::DISABLE_TYPE_ENFORCEMENT => true],
            );
        }
    }
}
