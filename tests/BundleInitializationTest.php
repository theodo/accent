<?php

declare(strict_types=1);

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Nyholm\BundleTest\TestKernel;
use ApiPlatform\Symfony\Bundle\ApiPlatformBundle;
use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\KernelInterface;
use Theodo\AccentBundle\TheodoAccentBundle;
use Theodo\AccentBundle\AccessControl\AccentReportFactory;
use Theodo\AccentBundle\AccessControl\RouteAccessControlData;

class BundleInitializationTest extends KernelTestCase
{
    protected static function getKernelClass(): string
    {
        return TestKernel::class;
    }

    protected static function createKernel(array $options = []): KernelInterface
    {
        /**
         * @var TestKernel $kernel
         */
        $kernel = parent::createKernel($options);
        $kernel->addTestBundle(DoctrineBundle::class);
        $kernel->addTestBundle(ApiPlatformBundle::class);
        $kernel->addTestBundle(TheodoAccentBundle::class);
        $kernel->addTestConfig(__DIR__.'/config/packages/doctrine.yaml');
        $kernel->addTestConfig(__DIR__.'/config/packages/api_platform.yaml');
        $kernel->addTestRoutingFile(__DIR__.'/config/routes.yaml');

        // Unused, non public services are removed during kernel boot.
        // We force them as public during test.
        $kernel->addTestCompilerPass(new class () implements CompilerPassInterface {
            public function process(ContainerBuilder $container): void
            {
                $services = $container->getDefinitions() + $container->getAliases();

                foreach ($services as $id => $definition) {
                    if (stripos($id, 'theodo_accent') === 0) {
                        $definition->setPublic(true);
                    }
                }
            }
        });

        $kernel->handleOptions($options);

        return $kernel;
    }

    public function testInitBundle(): void
    {
        $container = self::getContainer();

        $this->assertTrue($container->has('theodo_accent.access_control_checker_command'));
        $service = $container->get('theodo_accent.access_control_checker_command');
        $this->assertInstanceOf(Command::class, $service);
    }

    public function testExposedRoutes(): void
    {
        $container = self::getContainer();

        $this->assertTrue($container->has('theodo_accent.accent_report_factory'));

        /** @var AccentReportFactory $reportFactory */
        $reportFactory = $container->get('theodo_accent.accent_report_factory');
        $accentReport = $reportFactory->createAccentReport();

        $this->assertEquals(2, $accentReport->getUnprotectedRoutesCount());

        $resourceRelatedRoutes = array_filter(
            $accentReport->getRouteAccessControlList(),
            fn ($route) => RouteAccessControlData::RESOURCE_UNRELATED_ROUTE !== $route->getExpression()
        );

        $this->assertCount(3, $resourceRelatedRoutes);
        $checkedRoutes = [];
        foreach($resourceRelatedRoutes as $routeData) {
            $checkedRoutes[$routeData->getRouteName()] = $routeData->getExpression();
        }

        $expectedRoutes = [
            '_api_/books/{id}{._format}_get' => "is_granted('ROLE_USER_GET')",
            '_api_/books{._format}_post' => "is_granted('ROLE_USER_DEFAULT')",
            '_api_/books/{id}{._format}_patch' => "is_granted('ROLE_USER_PATCH')",
        ];

        $this->assertEquals($expectedRoutes, $checkedRoutes);
    }
}
