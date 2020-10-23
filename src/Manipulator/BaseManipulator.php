<?php

namespace Javanile\Producer\Manipulator;

use Composer\Factory;
use Composer\Json\JsonFile;
use Composer\Package\Version\VersionParser;
use Faker\Provider\Base;
use RuntimeException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Process\Process;

class BaseManipulator
{
    protected $command;

    /**
     * BaseManipulator constructor.
     *
     * @param $package
     * @param $version
     * @param $config
     */
    public function __construct($command)
    {
        $this->command = $command;
    }

    /**
     *
     * @param $package
     * @param $version
     *
     * @return BaseManipulator|LaravelManipulator
     */
    public static function createManipulator($package, $version, $command)
    {
        switch ($package) {
            case 'laravel/laravel':
                return new LaravelManipulator($command);
        }

        return new BaseManipulator($command);
    }
}
