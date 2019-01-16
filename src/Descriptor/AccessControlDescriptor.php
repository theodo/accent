<?php

namespace Socle\AccentBundle\Descriptor;

use Socle\AccentBundle\AccessControl\RouteAccessControlData;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\OutputInterface;

class AccessControlDescriptor
{
    private $output;

    /**
     * @param OutputInterface $output
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
                $routeAccessControl->getExpression(),
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
}
