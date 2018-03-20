<?php

namespace App\Serializer;

use \Neomerx\JsonApi\Schema\BaseSchema;
use \App\Entity\AttributeValue as AttributeValueEntity;

class AttributeValue extends BaseSchema
{
    protected $resourceType = 'attribute_values';

    public function getId($data): ?string
    {
        return $data->getId();
    }

    public function getAttributes($data, array $fieldKeysFilter = null): ?array
    {
        /** @var AttributeValueEntity $data */
        return [
            'value' => $data->getValue()
        ];
    }

    public function getRelationships($data, bool $isPrimary, array $includeRelationships): ?array
    {
        /** @var AttributeValueEntity $data */
        return [];
    }
}