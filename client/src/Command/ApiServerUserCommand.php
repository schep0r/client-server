<?php

namespace App\Command;

use App\Service\ApiUserHandler;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ApiServerUserCommand extends BaseApiCommand
{
    protected static $defaultName = 'api:server-user';
    private $apiUserHandler;

    public function __construct(ApiUserHandler $apiUserHandler)
    {
        $this->apiUserHandler = $apiUserHandler;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Command to communicate with server api')
            ->addArgument('action', InputArgument::REQUIRED, 'Action one of  ["list", "create", "update", "delete"]')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $this->input = $input;
        $this->output = $output;

        $action = $input->getArgument('action');

        switch ($action) {
            case "list":
                $this->list();
                break;
            case "create":
                $this->create();
                break;
            case "update":
                $this->update();
                break;
            case "delete":
                $this->delete();
                break;
            default:
                break;
        }

        $io->success('DONE!');

        return 0;
    }

    private function list()
    {
        $users = $this->apiUserHandler->list();
        $tableHeader = ['ID', 'Name', 'Email', 'Groups'];
        $this->showTable($tableHeader, $users);
    }

    private function create()
    {
        $data = $this->collectUserData();
        $this->apiUserHandler->create($data);
    }

    private function update()
    {
        $id = $this->askQuestion('Input user ID for update: ', 'ID can not be empty');
        $data = $this->collectUserData();
        $this->apiUserHandler->update($id, $data);
    }

    private function delete()
    {
        $id = $this->askQuestion('Input user ID for delete: ', 'ID can not be empty');
        $this->apiUserHandler->delete($id);
    }
}
