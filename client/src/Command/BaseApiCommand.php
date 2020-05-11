<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Question\Question;

class BaseApiCommand extends Command
{
    protected $input;
    protected $output;

    protected static $defaultName = 'base:api';

    public function __construct(string $name = null)
    {
        parent::__construct($name);
    }

    protected function configure()
    {
        $this
            ->setHidden(true)
        ;
    }

    public function askQuestion($question, $errorMessage = null)
    {
        $helper = $this->getHelper('question');

        $question = new Question($question);
        $question->setValidator(function ($value) use ($errorMessage) {
            if ($errorMessage !== null && trim($value) == '') {
                throw new \Exception($errorMessage);
            }

            return $value;
        });
        $question->setMaxAttempts(20);

        return $helper->ask($this->input, $this->output, $question);
    }

    public function collectUserData()
    {
        $data = [];

        $data['name'] = $this->askQuestion('Input user name: ', 'Name can not be empty');
        $data['email'] = $this->askQuestion('Input user email: ', 'Email can not be empty');
        $groups = $this->askQuestion('Input user groups using coma: ');
        $data['groups'] = explode(',', $groups);

        return $data;
    }

    public function showTable($header, $data)
    {
        $table = new Table($this->output);
        $table
            ->setHeaders($header)
            ->setRows($data)
        ;
        $table->render();
    }
}