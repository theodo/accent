<?php

declare(strict_types=1);

namespace Theodo\AccentBundle\AccessControl;

class RouteAccessControlJudge
{
    public const API_PLATFORM_DOCUMENTATION_ROUTES = [
        'api_entrypoint',
        'api_doc',
    ];

    public function isAccessControlCorrect(string $routeName, string $expression): bool
    {
        if ($this->hasNoAccessControl($expression)) {
            return false;
        }

        if ($this->isUnnecessaryExposedApiPlatformDocumentationRoute($routeName)) {
            return false;
        }

        return true;
    }

    protected function hasNoAccessControl(string $expression): bool
    {
        return RouteAccessControlData::NO_ACCESS_CONTROL === $expression;
    }

    protected function isUnnecessaryExposedApiPlatformDocumentationRoute(string $routeName): bool
    {
        return \in_array($routeName, self::API_PLATFORM_DOCUMENTATION_ROUTES, true);
    }
}
