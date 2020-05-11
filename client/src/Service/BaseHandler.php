<?php


namespace App\Service;

class BaseHandler
{
    protected $apiClient;

    public function __construct(ApiClient $apiClient)
    {
        $this->apiClient = $apiClient;

        $this->apiClient->setUrlSuffix($this->urlSuffix);
    }

    protected function modifyUserGroups($users)
    {
        foreach ($users as &$user) {
            $groups = array_column($user['groups'], 'name');
            $user['groups'] = implode(',', $groups);
        }

        return $users;
    }
}