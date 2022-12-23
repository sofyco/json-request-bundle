<?php declare(strict_types=1);

namespace Sofyco\Bundle\JsonRequestBundle\EventListener;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Serializer\Encoder\DecoderInterface;
use Symfony\Component\Serializer\SerializerInterface;

#[AsEventListener(event: KernelEvents::REQUEST)]
final readonly class JsonRequestListener
{
    public function __construct(private SerializerInterface $serializer)
    {
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();
        $content = (string) $request->getContent();
        $contentType = $request->getContentTypeFormat();

        if (null !== $contentType && false === empty($content) && $this->serializer instanceof DecoderInterface) {
            $request->request->add((array) $this->serializer->decode($content, $contentType));
        }
    }
}
