<?php

namespace App\Serializer;

use \Neomerx\JsonApi\Schema\BaseSchema;
use \Neomerx\JsonApi\Schema\BaseSchema as CategoryEntity;

class Category extends BaseSchema
{
    protected $resourceType = 'categories';

    public function getId($data): ?string
    {
        return $data->getId();
    }

    public function getAttributes($data, array $fieldKeysFilter = null): ?array
    {
        //@TODO: rewrite global variable
        global $kernel;
        /** @var CategoryEntity $data */
        return [
            'title' => $kernel->getContainer()->get('7cart.helper')->findRequestedTranslation($data->getTitle()),
            'parent-id'  => $data->getParentId(),
        ];
    }

    public function getRelationships($data, bool $isPrimary, array $includeRelationships): ?array
    {
        /** @var CategoryEntity $data */
        return [
            'children' => [self::DATA => $data->getChildren()],
        ];
    }
}