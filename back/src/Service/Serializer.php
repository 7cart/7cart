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

    public function serialize($data)
    {

        $encoder = Encoder::instance([
            'App\Entity\Category' => 'App\Serializer\Category',
            'App\Entity\Node' => 'App\Serializer\Node',
            'App\Entity\Attribute' => 'App\Serializer\Attribute',
            'App\Entity\AttributeValue' => 'App\Serializer\AttributeValue',
        ], new EncoderOptions(
            JSON_PRETTY_PRINT,
            $this->requestStack->getCurrentRequest()->getUriForPath('/api/v1')
        ));

        $options = new EncodingParameters([
             'attribute-values'
        ], [
            // Attributes and relationships that should be shown
            'categories'  => ['id', 'title', 'parent-id', 'children'],
            'nodes'  => ['id', 'title'],
            'attributes' => ['id', 'name', 'attribute-values'],
            'attribute_values' => ['id', 'value']
        ]);

        return $encoder->encodeData($data, $options);
    }
}

