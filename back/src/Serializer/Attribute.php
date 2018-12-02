<?php

namespace App\Serializer;

use \Neomerx\JsonApi\Schema\BaseSchema;
use \App\Entity\Attribute as AttributeEntity;

class Attribute extends BaseSchema
{
    protected $resourceType = 'attributes';

    public function getId($data): ?string
    {
        return $data->getId();
    }

    public function getAttributes($data, array $fieldKeysFilter = null): ?array
    {
        //@TODO: rewrite global variable
        global $kernel;
        /** @var AttributeEntity $data */
        return [
            'name' => $data->getName(),
            'data-type' => $data->getDataType()//$kernel->getContainer()->get('7cart.helper')->findRequestedTranslation($data->getTitle()),
        ];
    }

    public function getRelationships($data, bool $isPrimary, array $includeRelationships): ?array
    {
        /** @var AttributeEntity $data */
        return [
            'attribute-values' => [self::DATA => $data->getValues()],
        ];
    }
}