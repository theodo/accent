<?php

declare(strict_types=1);

namespace Theodo\AccentBundle\AccessControl;

use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\RouterInterface;

class AccentReportFactory
{
    private $routeAccessControlExtractor;
    private $routeAccessControlJudge;
    private $router;

    public function __construct(
        RouteAccessControlFactory $routeAccessControlExtractor,
        RouteAccessControlJudge $routeAccessControlJudge,
        RouterInterface $router
    ) {
        $this->routeAccessControlExtractor = $routeAccessControlExtractor;
        $this->routeAccessControlJudge = $routeAccessControlJudge;
        $this->router = $router;
    }

    public function createAccentReport(): AccentReport
    {
        $routes = $this->router->getRouteCollection();
        $report = new AccentReport();
        $routeAccessControlList = $this->getAllRoutesAccessControlData($routes);
        $unprotectedRoutesCount = $this->getUnprotectedRoutesCount($routeAccessControlList);

        $report->setRouteAccessControlList($routeAccessControlList);
        $report->setUnprotectedRoutesCount($unprotectedRoutesCount);

        return $report;
    }

    /**
     * @return RouteAccessControlData[]
     */
    public function getAllRoutesAccessControlData(RouteCollection $routes): array
    {
        $allAccessControlRouteData = [];

        /** @var string $name */
        foreach ($routes as $name => $route) {
            $accessControlRouteData = $this->routeAccessControlExtractor->createRouteAccessControlData($name, $route);
            $allAccessControlRouteData[] = $accessControlRouteData;
        }

        return $allAccessControlRouteData;
    }

    /**
     * @param RouteAccessControlData[] $routeAccessControlList
     *
     * @return int
     */
    public function getUnprotectedRoutesCount(array $routeAccessControlList)
    {
        $unprotectedRoutesCount = 0;

        foreach ($routeAccessControlList as $routeAccessControl) {
            if (!$routeAccessControl->isCorrect()) {
                ++$unprotectedRoutesCount;
            }
        }

        return $unprotectedRoutesCount;
    }
}
