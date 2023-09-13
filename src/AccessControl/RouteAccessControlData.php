<?php

declare(strict_types=1);

namespace Forge\AccentBundle\AccessControl;

use Symfony\Component\Routing\Route;

class RouteAccessControlData
{
    public const NO_ACCESS_CONTROL = 'NO_ACCESS_CONTROL';
    public const NOT_API_PLATFORM_ROUTE = 'NOT_API_PLATFORM_ROUTE';
    public const RESOURCE_NOT_FOUND = 'RESOURCE_NOT_FOUND';
    public const OPERATION_NOT_FOUND = 'OPERATION_NOT_FOUND';
    public const RESOURCE_UNRELATED_ROUTE = 'RESOURCE_UNRELATED_ROUTE';

    private $route;
    private $routeName;
    private $expression;
    private $correct;

    public function getRouteName(): string
    {
        return $this->routeName;
    }

    public function setRouteName(string $routeName): void
    {
        $this->routeName = $routeName;
    }

    public function getRoute(): Route
    {
        return $this->route;
    }

    public function setRoute(Route $route): void
    {
        $this->route = $route;
    }

    public function getExpression(): string
    {
        return $this->expression;
    }

    public function setExpression(string $expression): void
    {
        $this->expression = $expression;
    }

    public function isCorrect(): bool
    {
        return $this->correct;
    }

    public function setCorrect(bool $correct): void
    {
        $this->correct = $correct;
    }
}
