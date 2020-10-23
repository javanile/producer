<?php

namespace Javanile\Producer\Command;

use Composer\Factory;
use Composer\Json\JsonFile;
use Composer\Package\Version\VersionParser;
use Javanile\Producer\Manipulator\BaseManipulator;
use RuntimeException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Process\Process;

class NewCommand extends BaseCommand
{
    private $file;
    private $json;

    /**
     * Configure the command options.
     *
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName('new')
            ->setDescription('Create a new PHP application')
            ->addArgument('name', InputArgument::OPTIONAL)
            ->addOption('file', null, InputOption::VALUE_REQUIRED, 'Use local file as application template')
            //->addOption('dev', null, InputOption::VALUE_NONE, 'Installs the latest "development" release')
            //->addOption('jet', null, InputOption::VALUE_NONE, 'Installs the Laravel Jetstream scaffolding')
            //->addOption('stack', null, InputOption::VALUE_OPTIONAL, 'The Jetstream stack that should be installed')
            //->addOption('teams', null, InputOption::VALUE_NONE, 'Indicates whether Jetstream should be scaffolded with team support')
            ->addOption('force', 'f', InputOption::VALUE_NONE, 'Forces install even if the directory already exists')
            ;
    }

    /**
     * Execute the command.
     *
     * @param  \Symfony\Component\Console\Input\InputInterface  $input
     * @param  \Symfony\Component\Console\Output\OutputInterface  $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($input->getOption('file')) {
            $this->file = $input->getOption('file');
        } else {
            $this->file = './producer.json';
        }

        $this->json = new JsonFile($this->file);
        $config = $this->json->read();
        $config['from'];

        if (empty($config['from'])) {
            throw new RuntimeException('Empty "from" attribute on template file!');
        }

        $parser = new VersionParser();
        $from = $parser->parseNameVersionPairs(array($config['from']));
        $fromPackage = $from[0]['name'];
        $fromVersion = $from[0]['version'];

        $manipulator = BaseManipulator::createManipulator($fromPackage, $fromVersion, $config);
        /*
        if ($input->getOption('jet')) {
            $output->write(PHP_EOL."<fg=magenta>
    |     |         |
    |,---.|--- ,---.|--- ,---.,---.,---.,-.-.
    ||---'|    `---.|    |    |---',---|| | |
`---'`---'`---'`---'`---'`    `---'`---^` ' '</>".PHP_EOL.PHP_EOL);

            $stack = $this->jetstreamStack($input, $output);

            $teams = $input->getOption('teams') === true
                    ? (bool) $input->getOption('teams')
                    : (new SymfonyStyle($input, $output))->confirm('Will your application use teams?', false);
        } else {
            $output->write(PHP_EOL.'<fg=red> _                               _
| |                             | |
| |     __ _ _ __ __ ___   _____| |
| |    / _` | \'__/ _` \ \ / / _ \ |
| |___| (_| | | | (_| |\ V /  __/ |
|______\__,_|_|  \__,_| \_/ \___|_|</>'.PHP_EOL.PHP_EOL);
        }

        sleep(1);
        */

        $name = $input->getArgument('name');

        $directory = $name && $name !== '.' ? getcwd().'/'.$name : '.';

        //$version = $this->getVersion($input);

        /*
        if (! $input->getOption('force')) {
            $this->verifyApplicationDoesntExist($directory);
        }

        if ($input->getOption('force') && $directory === '.') {
            throw new RuntimeException('Cannot use --force option when using current directory for installation!');
        }
        */

        $composer = $this->findComposer();

        $commands = [
            $composer." create-project {$fromPackage} \"$directory\" {$fromVersion} --remove-vcs --prefer-dist",
        ];

        if ($directory != '.' && $input->getOption('force')) {
            if (PHP_OS_FAMILY == 'Windows') {
                array_unshift($commands, "rd /s /q \"$directory\"");
            } else {
                array_unshift($commands, "rm -rf \"$directory\"");
            }
        }

        if (PHP_OS_FAMILY != 'Windows') {
            $commands[] = "chmod 755 \"$directory/artisan\"";
        }

        if (($process = $this->runCommands($commands, $input, $output))->isSuccessful()) {
            $manipulator->postCreateProject();
            $output->writeln(PHP_EOL.'<comment>Application ready! Build something amazing.</comment>');
        }

        return $process->getExitCode();
    }

    /**
     * Verify that the application does not already exist.
     *
     * @param  string  $directory
     * @return void
     */
    protected function verifyApplicationDoesntExist($directory)
    {
        if ((is_dir($directory) || is_file($directory)) && $directory != getcwd()) {
            throw new RuntimeException('Application already exists!');
        }
    }

    /**
     * Get the version that should be downloaded.
     *
     * @param  \Symfony\Component\Console\Input\InputInterface  $input
     * @return string
     */
    protected function getVersion(InputInterface $input)
    {
        /*
        if ($input->getOption('dev')) {
            return 'dev-develop';
        }
        */

        return '';
    }
}
