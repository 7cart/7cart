<?php

namespace App\Serializer;

use \Neomerx\JsonApi\Schema\BaseSchema;

class User extends BaseSchema
{
    protected $resourceType = 'users';

    public function getId($data): ?string
    {
        return $data->getId();
    }

    public function getAttributes($data, array $fieldKeysFilter = null): ?array
    {
        return [
            'user-name' => $data->getUserName()
        ];
    }

}