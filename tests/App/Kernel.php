<?php declare(strict_types=1);

namespace Sofyco\Bundle\JsonRequestBundle\Tests\App;

use Sofyco\Bundle\JsonRequestBundle\JsonRequestBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

final class Kernel extends \Symfony\Component\HttpKernel\Kernel
{
    use MicroKernelTrait;

    public function registerBundles(): iterable
    {
        yield new FrameworkBundle();
        yield new JsonRequestBundle();
    }

    protected function configureContainer(ContainerConfigurator $container): void
    {
        $container->extension('framework', ['test' => true]);
    }

    protected function configureRoutes(RoutingConfigurator $routes): void
    {
        $routes->add('test', '/')->controller(__CLASS__);
    }

    public function __invoke(Request $request): JsonResponse
    {
        return new JsonResponse(data: $request->request->all());
    }
}
