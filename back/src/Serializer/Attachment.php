<?php

namespace App\Serializer;

use \Neomerx\JsonApi\Schema\BaseSchema;
use \App\Entity\Attachment as AttachmentEntity;

class Attachment extends BaseSchema
{
    protected $resourceType = 'attachments';

    public function getId($data): ?string
    {
        return $data->getId();
    }

    public function getAttributes($data, array $fieldKeysFilter = null): ?array
    {
        //@TODO: rewrite global variable
        global $kernel;
        /** @var AttachmentEntity $data */
        $baseUrl = $_ENV['FRONT_HOST'];
        return [
            'title' => $data->getTitle(),
            'file-name' => $baseUrl.$kernel->getContainer()->get('vich_uploader.templating.helper.uploader_helper')->asset($data, 'attachmentFile')
        ];
    }

    public function getRelationships($data, bool $isPrimary, array $includeRelationships): ?array
    {
        return [];
    }
}