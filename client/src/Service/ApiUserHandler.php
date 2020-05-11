<?php


namespace App\Service;

class ApiUserHandler extends BaseHandler
{
    protected $urlSuffix = 'users/';

    public function list()
    {
        $data = $this->apiClient->get();
        return $this->modifyUserGroups($data['users']);
    }

    public function create($data)
    {
        $this->apiClient->post($data);
    }

    public function update($id, $data)
    {
        $this->apiClient->patch($id, $data);
    }

    public function delete($id)
    {
        $this->apiClient->delete($id);
    }
}