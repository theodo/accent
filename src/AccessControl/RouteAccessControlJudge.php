<?php

declare(strict_types=1);

namespace Forge\AccentBundle\AccessControl;

class RouteAccessControlJudge
{
    const API_PLATFORM_DOCUMENTATION_ROUTES = [
        'api_entrypoint',
        'api_doc',
    ];

    /**
     * @param string $routeName
     * @param string $expression
     *
     * @return bool
     */
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

    /**
     * @param string $expression
     *
     * @return bool
     */
    protected function hasNoAccessControl(string $expression): bool
    {
        return RouteAccessControlData::NO_ACCESS_CONTROL === $expression;
    }

    /**
     * @param string $routeName
     *
     * @return bool
     */
    protected function isUnnecessaryExposedApiPlatformDocumentationRoute(string $routeName): bool
    {
        return \in_array($routeName, self::API_PLATFORM_DOCUMENTATION_ROUTES, true);
    }
}
