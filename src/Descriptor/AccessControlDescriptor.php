<?php

namespace Socle\AccentBundle\Descriptor;

use Socle\AccentBundle\AccessControl\RouteAccessControlData;
use Socle\AccentBundle\Command\AccessControlCheckerCommand;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\OutputInterface;

class AccessControlDescriptor
{
    const ACCESS_CONTROL_TRANSLATIONS = [
      AccessControlCheckerCommand::NO_ACCESS_CONTROL => '<fg=white;bg=red>Pas de contrôle d\'accès.</>',
      AccessControlCheckerCommand::NOT_API_PLATFORM_ROUTE => 'Cette route n\'est pas liée à API Platform.',
      AccessControlCheckerCommand::RESOURCE_NOT_FOUND => 'La ressource liée à cette route est introuvable.',
      AccessControlCheckerCommand::RESOURCE_UNRELATED_ROUTE => 'Cette route n\'est pas liée à une ressource.',
    ];
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
