<?php declare(strict_types=1);

namespace Sofyco\Bundle\JsonRequestBundle\EventListener;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Serializer\Encoder\DecoderInterface;
use Symfony\Component\Serializer\SerializerInterface;

#[AsEventListener(event: KernelEvents::REQUEST, priority: 512)]
final readonly class JsonRequestListener
{
    public function __construct(private SerializerInterface $serializer)
    {
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $content = $event->getRequest()->getContent();
        $contentType = $event->getRequest()->getContentTypeFormat();

        if (null !== $contentType && false === empty($content) && $this->serializer instanceof DecoderInterface) {
            /** @var array<string, mixed> $inputs */
            $inputs = (array) $this->serializer->decode(data: $content, format: $contentType);

            $event->getRequest()->request->add(inputs: $inputs);
        }
    }
}
