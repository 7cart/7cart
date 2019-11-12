<?php

namespace App\Service;

use Neomerx\JsonApi\Encoder\Encoder;
use Neomerx\JsonApi\Encoder\EncoderOptions;
use Neomerx\JsonApi\Encoder\Parameters\EncodingParameters;
use Symfony\Component\HttpFoundation\RequestStack;

class Serializer
{

    protected $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function serialize($data, $meta = null)
    {

        $encoder = Encoder::instance([
            'App\Entity\Attachment' => 'App\Serializer\Attachment',
            'App\Entity\Category' => 'App\Serializer\Category',
            'App\Entity\Node' => 'App\Serializer\Node',
            'App\Entity\Attribute' => 'App\Serializer\Attribute',
            'App\Entity\AttributeValue' => 'App\Serializer\AttributeValue',
            'Proxies\__CG__\App\Entity\User' => 'App\Serializer\User',
            'App\Entity\User' => 'App\Serializer\User',
        ], new EncoderOptions(
            JSON_PRETTY_PRINT,
            $this->requestStack->getCurrentRequest()->getUriForPath('/api/v1')
        ));

        $options = new EncodingParameters([
             'attribute-values',
             'attachments'
        ], [
            // Attributes and relationships that should be shown
            'categories'  => ['id', 'title', 'parent-id', 'children'],
            'nodes'  => ['id', 'title', 'attachments', 'description', 'attributes', 'categories-id'],
            'attachments'  => ['id', 'title', 'file-name'],
            'attributes' => ['id', 'name', 'attribute-values', 'is-multi-values', 'is-related', 'is-numeric',  'data-type'],
            'attribute-values' => ['id', 'value'],
            'users' => ['id', 'user-name']
        ]);

        return $encoder->withMeta($meta)->encodeData($data, $options);
    }
}

