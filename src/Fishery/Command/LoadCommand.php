<?php

namespace Lc\Fishery\Command;

use Lc\Fishery\Config\Formatter\YamlEntitiesDefinitionFormatter;
use Lc\Fishery\Schema\Loader\EntityBulkLoader;
use Symfony\Component\Console\Command\Command as BaseCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class Command
 *
 * @package Lc\Fishery\Command
 *
 * @author  Laurent Callarec <l.callarec@gmail.com>
 */
class LoadCommand extends BaseCommand
{
    protected function configure()
    {
        $this
            ->setName('load')
            ->setDescription('Load data')
            ->addArgument(
                'name',
                InputArgument::OPTIONAL,
                'Qui voulez-vous saluez?'
            )
            ->addOption(
                'yell',
                null,
                InputOption::VALUE_NONE,
                'Si défini, la réponse est affichée en majuscules'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $entitiesDefinitionFormatter = new YamlEntitiesDefinitionFormatter();

        $schemaManager = $this->getApplication()->getContainer()['schema.manager'];

        $schemaManager->buildSchemas();

        $def = $this->getApplication()->getContainer()['entity.definition'];

        $entityPersister = new EntityBulkLoader($schemaManager, $entitiesDefinitionFormatter);

        $entityPersister->load($def);
    }

    /**
     * {@inheritdoc}
     */
    public function isEnabled()
    {
        if (version_compare(phpversion(), '5.4.0', '<') || defined('HHVM_VERSION')) {
            return false;
        }

        return parent::isEnabled();
    }

    /**
     * {@inheritdoc}
     */
    protected function configgure()
    {
        $this
            ->setDefinition(array(
                new InputArgument('address', InputArgument::OPTIONAL, 'Address:port', '127.0.0.1:8000'),
                new InputOption('docroot', 'd', InputOption::VALUE_REQUIRED, 'Document root', 'web/'),
                new InputOption('router', 'r', InputOption::VALUE_REQUIRED, 'Path to custom router script'),
            ))
            ->setName('server:run')
            ->setDescription('Runs PHP built-in web server')
            ->setHelp(<<<EOF
The <info>%command.name%</info> runs PHP built-in web server:

  <info>%command.full_name%</info>

To change default bind address and port use the <info>address</info> argument:

  <info>%command.full_name% 127.0.0.1:8080</info>

To change default docroot directory use the <info>--docroot</info> option:

  <info>%command.full_name% --docroot=htdocs/</info>

If you have custom docroot directory layout, you can specify your own
router script using <info>--router</info> option:

  <info>%command.full_name% --router=app/config/router.php</info>

Specifing a router script is required when the used environment is not "dev" or
"prod".

See also: http://www.php.net/manual/en/features.commandline.webserver.php

EOF
            )
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execgute(InputInterface $input, OutputInterface $output)
    {
        $documentRoot = $input->getOption('docroot');

        if (!is_dir($documentRoot)) {
            $output->writeln(sprintf('<error>The given document root directory "%s" does not exist</error>', $documentRoot));

            return 1;
        }

        $env = $this->getContainer()->getParameter('kernel.environment');

        if ('prod' === $env) {
            $output->writeln('<error>Running PHP built-in server in production environment is NOT recommended!</error>');
        }

        $output->writeln(sprintf("Server running on <info>http://%s</info>\n", $input->getArgument('address')));

        $builder = $this->createPhpProcessBuilder($input, $output, $env);
        $builder->setWorkingDirectory($documentRoot);
        $builder->setTimeout(null);
        $process = $builder->getProcess();

        if (OutputInterface::VERBOSITY_VERBOSE <= $output->getVerbosity()) {
            $callback = function ($type, $buffer) use ($output) {
                $output->write($buffer);
            };
        } else {
            $callback = null;
            $process->disableOutput();
        }

        $process->run($callback);
    }

    private function createPhpProcessBuilder(InputInterface $input, OutputInterface $output, $env)
    {
        $router = $input->getOption('router') ?: $this
            ->getContainer()
            ->get('kernel')
            ->locateResource(sprintf('@FrameworkBundle/Resources/config/router_%s.php', $env))
        ;

        return new ProcessBuilder(array(PHP_BINARY, '-S', $input->getArgument('address'), $router));
    }

}
