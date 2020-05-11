<?php

namespace App\Command;

use App\Service\ApiGroupHandler;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ApiServerGroupCommand extends BaseApiCommand
{
    protected static $defaultName = 'api:server-group';
    private $apiGroupHandler;

    public function __construct(ApiGroupHandler $apiGroupHandler)
    {
        $this->apiGroupHandler = $apiGroupHandler;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Command to communicate with server api')
            ->addArgument('action', InputArgument::REQUIRED, 'Action one of  ["list", "create", "update", "delete", "users-list"]')
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
            case "users-list":
                $this->usersList();
                break;
            default:
                break;
        }

        $io->success('DONE!');

        return 0;
    }

    private function list()
    {
        $groups = $this->apiGroupHandler->list();
        $tableHeader = ['ID', 'Name'];
        $this->showTable($tableHeader, $groups);
    }

    private function create()
    {
        $data = $this->collectGroupData();
        $this->apiGroupHandler->create($data);
    }

    private function update()
    {
        $id = $this->askQuestion('Input group ID for update: ', 'ID can not be empty');
        $data = $this->collectGroupData();
        $this->apiGroupHandler->update($id, $data);
    }

    private function delete()
    {
        $id = $this->askQuestion('Input group ID for delete: ', 'ID can not be empty');
        $this->apiGroupHandler->delete($id);
    }

    private function collectGroupData()
    {
        $data = [];
        $data['name'] = $this->askQuestion('Input group name: ', 'Name can not be empty');
        return $data;
    }

    private function usersList()
    {
        $id = $this->askQuestion('Input group ID: ', 'ID can not be empty');
        $users = $this->apiGroupHandler->getUsers($id);
        $tableHeader = ['ID', 'Name', 'Email', 'Groups'];
        $this->showTable($tableHeader, $users);
    }
}
