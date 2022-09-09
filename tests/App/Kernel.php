<?php declare(strict_types=1);

namespace Sofyco\Bundle\JsonRequestBundle\Tests\App;

use Sofyco\Bundle\JsonRequestBundle\Attribute\DTO;
use Sofyco\Bundle\JsonRequestBundle\Tests\App\Attribute\Unsupported;
use Sofyco\Bundle\JsonRequestBundle\Tests\App\Request\ExampleDTO;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

final class Kernel extends \Symfony\Component\HttpKernel\Kernel
{
    use MicroKernelTrait;

    public function registerBundles(): iterable
    {
        yield new \Symfony\Bundle\FrameworkBundle\FrameworkBundle();
        yield new \Sofyco\Bundle\JsonRequestBundle\JsonRequestBundle();
    }

    protected function configureContainer(ContainerConfigurator $container): void
    {
        $container->extension('framework', ['test' => true]);
    }

    protected function configureRoutes(RoutingConfigurator $routes): void
    {
        $routes->add('test', '/{id<\d+>}')->controller(__CLASS__);
    }

    public function __invoke(string $id, #[DTO] ExampleDTO $example, #[Unsupported] int $unsupported = 1): JsonResponse
    {
        return new JsonResponse($example);
    }
}
