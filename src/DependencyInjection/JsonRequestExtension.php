<?php declare(strict_types=1);

namespace Sofyco\Bundle\JsonRequestBundle\DependencyInjection;

use Sofyco\Bundle\JsonRequestBundle\EventListener\JsonRequestListener;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\Extension;

final class JsonRequestExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $jsonRequestListener = new Definition(JsonRequestListener::class);
        $jsonRequestListener->setAutowired(true);
        $jsonRequestListener->setAutoconfigured(true);
        $container->setDefinition(JsonRequestListener::class, $jsonRequestListener);
    }
}
