<?php

namespace App\Serializer;

use \Neomerx\JsonApi\Schema\BaseSchema;
use \App\Entity\Node as NodeEntity;

class Node extends BaseSchema
{
    protected $resourceType = 'nodes';

    public function getId($data): ?string
    {
        return $data->getId();
    }

    public function getAttributes($data, array $fieldKeysFilter = null): ?array
    {
        //@TODO: rewrite global variable
        global $kernel;
        /** @var NodeEntity $data */
        return [
            'title' => $kernel->getContainer()->get('7cart.helper')->findRequestedTranslation($data->getTitle()),
        ];
    }

    public function getRelationships($data, bool $isPrimary, array $includeRelationships): ?array
    {
        /** @var NodeEntity $data */
        return ['attachments' => [self::DATA => $data->getAttachments()]];
    }
}