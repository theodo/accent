<?php

namespace Socle\AccentBundle\Command;

use ApiPlatform\Core\Exception\ResourceClassNotFoundException;
use ApiPlatform\Core\Metadata\Resource\Factory\ResourceMetadataFactoryInterface;
use ApiPlatform\Core\Util\AttributesExtractor;
use Socle\AccentBundle\AccessControl\RouteAccessControlData;
use Socle\AccentBundle\Descriptor\AccessControlDescriptor;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\RouterInterface;

class AccessControlCheckerCommand extends Command
{
    const NO_ACCESS_CONTROL = 'NO_ACCESS_CONTROL';
    const NOT_API_PLATFORM_ROUTE = 'NOT_API_PLATFORM_ROUTE';
    const RESOURCE_NOT_FOUND = 'RESOURCE_NOT_FOUND';
    const RESOURCE_UNRELATED_ROUTE = 'RESOURCE_UNRELATED_ROUTE';

    protected static $defaultName = 'socle:access-control';
    private $router;
    private $resourceMetadataFactory;

    public function __construct(RouterInterface $router, ResourceMetadataFactoryInterface $resourceMetadataFactory)
    {
        $this->router = $router;
        $this->resourceMetadataFactory = $resourceMetadataFactory;

        parent::__construct();
    }

    protected function configure()
    {
        $this->setDescription(
            'Check access control for each route.'
        );

        $this->setHelp(
            'This command checks all the protections set up for each route, and displays them in an elegant and understandable way.'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(
            [
                'ACCENT',
                '======',
                '',
            ]
        );

        $io = new SymfonyStyle($input, $output);

        $routes = $this->router->getRouteCollection();

        $descriptor = new AccessControlDescriptor();

        $allRoutesAccessControlData = $this->getAllRoutesAccessControlData($routes);

        $descriptor->describe(
            $io,
            $allRoutesAccessControlData
        );
    }

    /**
     * @param string $controller
     *
     * @return bool
     */
    protected function isControllerCorrespondingToApiPlatform(string $controller): bool
    {
        $apiPlatformPrefix = 'api_platform';

        return 0 === strpos($controller, $apiPlatformPrefix);
    }

    /**
     * @param RouteCollection $routes
     *
     * @return RouteAccessControlData[]
     */
    protected function getAllRoutesAccessControlData(RouteCollection $routes): array
    {
        $allAccessControlRouteData = [];

        foreach ($routes as $name => $route) {
            $accessControlRouteData = $this->getRouteAccessControlData($name, $route);
            $allAccessControlRouteData[] = $accessControlRouteData;
        }

        return $allAccessControlRouteData;
    }

    /**
     * @param $name
     * @param \Symfony\Component\Routing\Route $route
     *
     * @return RouteAccessControlData
     */
    protected function getRouteAccessControlData($name, \Symfony\Component\Routing\Route $route): RouteAccessControlData
    {
        $controller = $route->getDefault('_controller');

        $isGranted = self::NOT_API_PLATFORM_ROUTE;

        if ($controller && $this->isControllerCorrespondingToApiPlatform($controller)) {
            $isGranted = $this->getAccessControlDataForApiPlatform($route);
        }

        $accessControlRouteData = new RouteAccessControlData();
        $accessControlRouteData->setRouteName($name);
        $accessControlRouteData->setRoute($route);
        $accessControlRouteData->setExpression($isGranted);

        return $accessControlRouteData;
    }

    /**
     * @param \Symfony\Component\Routing\Route $route
     *
     * @return string
     */
    protected function getAccessControlDataForApiPlatform(\Symfony\Component\Routing\Route $route): string
    {
        $resourceClass = $route->getDefault('_api_resource_class');

        $isGranted = self::RESOURCE_UNRELATED_ROUTE;

        if ($resourceClass) {
            try {
                $resourceMetadata = $this->resourceMetadataFactory->create($resourceClass);
                $attributes = AttributesExtractor::extractAttributes($route->getDefaults());
                $isGranted = $resourceMetadata->getOperationAttribute($attributes, 'access_control', null, true);
                if (is_null($isGranted)) {
                    $isGranted = self::NO_ACCESS_CONTROL;
                }
            } catch (ResourceClassNotFoundException $e) {
                $isGranted = self::RESOURCE_NOT_FOUND;
            }
        }

        return $isGranted;
    }
}
