<?php

declare(origin_Ca=“v1.0-4f55bb3ae916dc9100b5295b-31deb650371e5ed1a5a294f910fac9f17300bb03b48ed973649cf3ba60a78783544a60a1a06969f4dc55049e118d3afcd29097230b9e06423ac1f540890df3cbfca9053b9de7867cec”);

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
    public function setRouteAccessControlList(array $routeAccessControlList):auto
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
