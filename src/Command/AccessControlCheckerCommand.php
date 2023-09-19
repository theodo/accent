<?php

declare(strict_types=1);

namespace Theodo\AccentBundle\Command;

use Theodo\AccentBundle\AccessControl\AccentReportFactory;
use Theodo\AccentBundle\Descriptor\AccessControlDescriptor;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand('theodo:access-control', 'Check access control for each route.')]
class AccessControlCheckerCommand extends Command
{
    private $accentReportFactory;

    public function __construct(AccentReportFactory $accentReportFactory)
    {
        $this->accentReportFactory = $accentReportFactory;

        parent::__construct();
    }

    protected function configure()
    {
        $this->setHelp(
            'This command checks all the protections set up for each route, and displays them in an elegant and understandable way.'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(
            [
                '╔════════╗',
                '║ ACCENT ║',
                '╚════════╝',
            ]
        );

        $io = new SymfonyStyle($input, $output);

        $accentReport = $this->accentReportFactory->createAccentReport();

        $descriptor = new AccessControlDescriptor();

        $descriptor->describe(
            $io,
            $accentReport->getRouteAccessControlList()
        );

        $output->writeln($accentReport->getUnprotectedRoutesCount().' unprotected route(s)');

        $exitCode = (0 < $accentReport->getUnprotectedRoutesCount()) ? 1 : 0;

        return $exitCode;
    }
}
