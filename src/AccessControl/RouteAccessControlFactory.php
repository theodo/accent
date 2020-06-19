<?php

declare(strict_types=1);

namespace Forge\AccentBundle\AccessControl;

use ApiPlatform\Core\Exception\ResourceClassNotFoundException;
use ApiPlatform\Core\Metadata\Resource\Factory\ResourceMetadataFactoryInterface;
use ApiPlatform\Core\Util\AttributesExtractor;
use Symfony\Component\Routing\Route;

class RouteAccessControlFactory
{
    private $resourceMetadataFactory;
    private $judge;

    public function __construct(
        ResourceMetadataFactoryInterface $resourceMetadataFactory,
        RouteAccessControlJudge $routeAccessControlJudge
    ) {
        $this->resourceMetadataFactory = $resourceMetadataFactory;
        $this->judge = $routeAccessControlJudge;
    }

    /**
     * @param string $name
     * @param Route  $route
     *
     * @return RouteAccessControlData
     */
    public function createRouteAccessControlData(string $name, Route $route): RouteAccessControlData
    {
        $controller = $route->getDefault('_controller');

        $expression = RouteAccessControlData::NOT_API_PLATFORM_ROUTE;

        if ($controller && $this->isControllerCorrespondingToApiPlatform($controller)) {
            $expression = $this->getAccessControlExpressionForApiPlatform($route);
        }

        $isRouteCorrect = $this->judge->isAccessControlCorrect($name, $expression);

        $accessControlRouteData = new RouteAccessControlData();
        $accessControlRouteData->setRouteName($name);
        $accessControlRouteData->setRoute($route);
        $accessControlRouteData->setExpression($expression);
        $accessControlRouteData->setCorrect($isRouteCorrect);

        return $accessControlRouteData;
    }

    /**
     * @param string $controller
     *
     * @return bool
     */
    protected function isControllerCorrespondingToApiPlatform(string $controller): bool
    {
        $apiPlatformPrefix = 'api_platform';

        return 0 === mb_strpos($controller, $apiPlatformPrefix);
    }

    /**
     * @param Route $route
     *
     * @return string
     */
    protected function getAccessControlExpressionForApiPlatform(Route $route): string
    {
        $resourceClass = $route->getDefault('_api_resource_class');

        $isGranted = RouteAccessControlData::RESOURCE_UNRELATED_ROUTE;

        if ($resourceClass) {
            try {
                $resourceMetadata = $this->resourceMetadataFactory->create($resourceClass);
                $attributes = AttributesExtractor::extractAttributes($route->getDefaults());
                $isGranted = $resourceMetadata->getOperationAttribute($attributes, 'security', null, true);
                if (null === $isGranted) {
                    $isGranted = RouteAccessControlData::NO_ACCESS_CONTROL;
                }
            } catch (ResourceClassNotFoundException $e) {
                $isGranted = RouteAccessControlData::RESOURCE_NOT_FOUND;
            }
        }

        return $isGranted;
    }
}
