<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class LocaleKeyValueType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            #@TODO create table with locales
            $locales = ['en'];
            $fieldData = $event->getData();
            $form = $event->getForm();


            if ($fieldData && is_array($fieldData)) {
                foreach ($fieldData as $key => $value) {
                    $form->add($key, TextType::class, array('label' => $key, 'data' => $value));
                }
            }

            foreach ($locales as $locale){
                if (!$form->has($locale))  {
                    $form->add($locale, TextType::class, array('label' => $locale, 'data' => ''));
                }
            }

        });
    }

}