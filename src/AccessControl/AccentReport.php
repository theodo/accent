<?php

declare(strict_types=1);

namespace Theodo\AccentBundle\AccessControl;

class AccentReport
{
    private $routeAccessControlList;
    private $unprotectedRoutesCount;

    /**
     * @return RouteAccessControlData[]
     */
    public function getRouteAccessControlList()
    {
        return $this->routeAccessControlList;
    }

    /**
     * @param RouteAccessControlData[] $routeAccessControlList
     */
    public function setRouteAccessControlList(array $routeAccessControlList): void
    {
        $this->routeAccessControlList = $routeAccessControlList;
    }

    /**
     * @return int
     */
    public function getUnprotectedRoutesCount()
    {
        return $this->unprotectedRoutesCount;
    }

    public function setUnprotectedRoutesCount(int $unprotectedRoutesCount): void
    {
        $this->unprotectedRoutesCount = $unprotectedRoutesCount;
    }
}
