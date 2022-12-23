<?php declare(strict_types=1);

namespace Sofyco\Bundle\JsonRequestBundle\DependencyInjection;

use Sofyco\Bundle\JsonRequestBundle\ArgumentResolver\DataTransferObjectArgumentResolver;
use Sofyco\Bundle\JsonRequestBundle\EventListener\JsonRequestListener;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\Extension;

final class JsonRequestExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $argumentResolver = new Definition(DataTransferObjectArgumentResolver::class);
        $argumentResolver->setAutowired(true);
        $argumentResolver->addTag('controller.argument_value_resolver');
        $container->setDefinition(DataTransferObjectArgumentResolver::class, $argumentResolver);

        $jsonRequestListener = new Definition(JsonRequestListener::class);
        $jsonRequestListener->setAutowired(true);
        $jsonRequestListener->setAutoconfigured(true);
        $container->setDefinition(JsonRequestListener::class, $jsonRequestListener);
    }
}
