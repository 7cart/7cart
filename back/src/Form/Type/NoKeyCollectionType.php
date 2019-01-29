<?php

namespace App\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\CallbackTransformer;

class NoKeyCollectionType extends CollectionType
{


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder->addModelTransformer(new CallbackTransformer(
            function ($collection) {
                return $collection;
            },
            function ($collection) {
                //Do'nt save keys in array
                return array_values($collection);
            }
        ));
    }

}