<?php

declare(strict_types=1);

namespace Forge\AccentBundle\Descriptor;

use Forge\AccentBundle\AccessControl\RouteAccessControlData;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\OutputInterface;

class AccessControlDescriptor
{
    const ACCESS_CONTROL_TRANSLATIONS = [
        RouteAccessControlData::NO_ACCESS_CONTROL => '<fg=white;bg=red>No access control.</>',
        RouteAccessControlData::NOT_API_PLATFORM_ROUTE => 'This route is not linked to API Platform.',
        RouteAccessControlData::RESOURCE_NOT_FOUND => 'The resource linked to his route was not found.',
        RouteAccessControlData::RESOURCE_UNRELATED_ROUTE => 'This route is not linked to a resource',
    ];
    private $output;

    /**
     * @param OutputInterface          $output
     * @param RouteAccessControlData[] $routeAccessControlData
     */
    public function describe(OutputInterface $output, array $routeAccessControlData)
    {
        $this->output = $output;

        $tableHeaders = ['Name', 'Method', 'Path', 'Access Control'];

        $tableRows = [];
        foreach ($routeAccessControlData as $routeAccessControl) {
            $route = $routeAccessControl->getRoute();
            $row = [
                $routeAccessControl->getRouteName(),
                $route->getMethods() ? implode('|', $route->getMethods()) : 'ANY',
                $route->getPath(),
                $this->translate($routeAccessControl->getExpression()),
            ];

            $tableRows[] = $row;
        }

        $table = new Table($this->getOutput());
        $table->setHeaders($tableHeaders)->setRows($tableRows);
        $table->render();
    }

    /**
     * @return OutputInterface
     */
    public function getOutput(): OutputInterface
    {
        return $this->output;
    }

    public function translate(string $expression)
    {
        if (isset(self::ACCESS_CONTROL_TRANSLATIONS[$expression])) {
            return self::ACCESS_CONTROL_TRANSLATIONS[$expression];
        }

        return $expression;
    }
}
