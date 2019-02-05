<?php

declare(strict_types=1);

namespace Forge\AccentBundle\AccessControl;

use Symfony\Component\Routing\Route;

class RouteAccessControlData
{
    const NO_ACCESS_CONTROL = 'NO_ACCESS_CONTROL';
    const NOT_API_PLATFORM_ROUTE = 'NOT_API_PLATFORM_ROUTE';
    const RESOURCE_NOT_FOUND = 'RESOURCE_NOT_FOUND';
    const RESOURCE_UNRELATED_ROUTE = 'RESOURCE_UNRELATED_ROUTE';

    private $route;
    private $routeName;
    private $expression;
    private $correct;

    /**
     * @return string
     */
    public function getRouteName(): string
    {
        return $this->routeName;
    }

    /**
     * @param string $routeName
     */
    public function setRouteName(string $routeName): void
    {
        $this->routeName = $routeName;
    }

    /**
     * @return Route
     */
    public function getRoute(): Route
    {
        return $this->route;
    }

    /**
     * @param Route $route
     */
    public function setRoute(Route $route): void
    {
        $this->route = $route;
    }

    /**
     * @return string
     */
    public function getExpression(): string
    {
        return $this->expression;
    }

    /**
     * @param string $expression
     */
    public function setExpression(string $expression): void
    {
        $this->expression = $expression;
    }

    /**
     * @return bool
     */
    public function isCorrect(): bool
    {
        return $this->correct;
    }

    /**
     * @param bool $correct
     */
    public function setCorrect(bool $correct): void
    {
        $this->correct = $correct;
    }
}
