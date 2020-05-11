<?php


namespace App\Service;

class ApiGroupHandler extends BaseHandler
{
    protected $urlSuffix = 'groups/';

    public function list()
    {
        $data = $this->apiClient->get();
        return $data['groups'];
    }

    public function create($data)
    {
        return $this->apiClient->post($data);
    }

    public function update($id, $data)
    {
        return $this->apiClient->patch($id, $data);
    }

    public function delete($id)
    {
        return $this->apiClient->delete($id);
    }

    public function getUsers($id)
    {
        $data = $this->apiClient->getGroupUsers($id);
        return $this->modifyUserGroups($data['users']);
    }
}