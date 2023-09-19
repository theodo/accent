<?php

declare(strict_types=1);

namespace Forge\AccentBundle\AccessControl;

use ApiPlatform\Exception\OperationNotFoundException;
use ApiPlatform\Exception\ResourceClassNotFoundException;
use ApiPlatform\Metadata\Resource\Factory\ResourceMetadataCollectionFactoryInterface;
use Symfony\Component\Routing\Route;

class RouteAccessControlFactory
{
    public function __construct(
        private ?ResourceMetadataCollectionFactoryInterface $resourceMetadataCollectionFactory,
        private RouteAccessControlJudge $judge
    ) {
    }

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

    protected function isControllerCorrespondingToApiPlatform(string $controller): bool
    {
        $apiPlatformPrefix = 'api_platform';

        return 0 === mb_strpos($controller, $apiPlatformPrefix);
    }

    protected function getAccessControlExpressionForApiPlatform(Route $route): string
    {
        $resourceClass = $route->getDefault('_api_resource_class');
        $operationName = $route->getDefault('_api_operation_name');

        $isGranted = RouteAccessControlData::RESOURCE_UNRELATED_ROUTE;

        if ($resourceClass) {
            try {
                $resourceMetadata = $this->resourceMetadataCollectionFactory->create($resourceClass);
                $operation = $resourceMetadata->getOperation($operationName);

                $isGranted = $operation->getSecurity();
                if (null === $isGranted) {
                    $isGranted = RouteAccessControlData::NO_ACCESS_CONTROL;
                }
            } catch (ResourceClassNotFoundException $e) {
                $isGranted = RouteAccessControlData::RESOURCE_NOT_FOUND;
            } catch (OperationNotFoundException $e) {
                $isGranted = RouteAccessControlData::OPERATION_NOT_FOUND;
            }
        }

        return $isGranted;
    }
}
