<?php

namespace Javanile\Producer\Manipulator;

use Composer\Factory;
use Composer\Json\JsonFile;
use Composer\Package\Version\VersionParser;
use RuntimeException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Process\Process;

class LaravelManipulator extends BaseManipulator
{

}
