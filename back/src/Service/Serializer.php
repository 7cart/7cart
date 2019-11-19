<?php

namespace App\Service;

use Neomerx\JsonApi\Encoder\Encoder;
use Neomerx\JsonApi\Encoder\EncoderOptions;
use Neomerx\JsonApi\Encoder\Parameters\EncodingParameters;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Form\Form;

class Serializer
{

    protected $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function serializeFormError(Form $form)
    {

        $errors = new \Neomerx\JsonApi\Exceptions\ErrorCollection();

        foreach ($this->_buildErrorArray($form) as $field => $error) {
            $errors->add(new \Neomerx\JsonApi\Document\Error(
                null,
                null,
                '422',
                null,
                $error,
                $error,
                ['pointer' => "data/attributes/" . str_replace('data.','',$field)]
            ));
        }

        return Encoder::instance()->encodeErrors($errors);
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
            'categories' => ['id', 'title', 'parent-id', 'children'],
            'nodes' => ['id', 'title', 'attachments', 'description', 'attributes', 'categories-id'],
            'attachments' => ['id', 'title', 'file-name'],
            'attributes' => ['id', 'name', 'attribute-values', 'is-multi-values', 'is-related', 'is-numeric', 'data-type'],
            'attribute-values' => ['id', 'value'],
            'users' => ['id', 'name']
        ]);

        return $encoder->withMeta($meta)->encodeData($data, $options);
    }

    private function _buildErrorArray(Form $form)
    {
        $errors = [];

        foreach ($form->all() as $child) {
            $errors = array_merge(
                $errors,
                $this->_buildErrorArray($child)
            );
        }

        foreach ($form->getErrors() as $error) {
            $errors[$error->getCause()->getPropertyPath()] = $error->getMessage();
        }

        return $errors;
    }
}

