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
        ], new EncoderOptions(
            JSON_PRETTY_PRINT,
            $this->requestStack->getCurrentRequest()->getUriForPath('/api/v1')
        ));

        $options = new EncodingParameters([
            // Paths to be included. Note 'posts.comments' will not be shown.
            // 'children'
        ], [
            // Attributes and relationships that should be shown
            'categories'  => ['id', 'title', 'parent-id', 'children'],
            //'articles'  => [],
            //'people' => ['first_name'],
        ]);

        return $encoder->encodeData($data, $options);
    }
}

