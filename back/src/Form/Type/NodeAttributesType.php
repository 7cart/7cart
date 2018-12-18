<?php

namespace App\Form\Type;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

use Symfony\Component\Validator\Constraints\Type;

use App\Entity\Attribute;

class NodeAttributesType extends AbstractType
{

    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $fieldData = $event->getData();

            $form = $event->getForm();
            /** @var Attribute $attribute */
            $attributes = $this->em->getRepository(Attribute::class)->findAll();
            foreach ($attributes as $attribute) {
                $key = $attribute->getName();
                $currentValue = isset($fieldData[$key]) ? $fieldData[$key] : null;
                $isMultiple = $attribute->isMultiValues();

                if ($isMultiple && !is_array($currentValue)) {
                    $currentValue = [(int)$currentValue];
                } else if (!$isMultiple && is_array($currentValue)) {
                    $currentValue = $currentValue[0];
                }

                $formEntryType = TextType::class;
                switch ($attribute->getDataType()) {
                    case 'integer':
                        $formEntryType = IntegerType::class;
                        break;
                    case 'numeric':
                        $formEntryType = NumberType::class;
                        break;
                }

                $constraints = new Type([
                    'type' => $attribute->getDataType(),
                    'message' => 'The value {{ value }} is not a valid {{ type }}.'
                ]);

                if ($attribute->isRelated()) {
                    $choices = [];
                    foreach ($attribute->getValues() as $value) {
                        $choices[$value->getValue()] = $value->getId();
                    }

                    $form->add($key, ChoiceType::class, array(
                        'label' => $key,
                        'data' => $currentValue,
                        'multiple' => $isMultiple,
                        'choices' => $choices,
                    ));
                } else {
                    if ($attribute->isMultiValues()) {
                        $form->add($key, NoKeyCollectionType::class, array(
                            'entry_type' => $formEntryType,
                            'entry_options' => ['constraints' => $constraints],
                            'data' => $currentValue,
                            'allow_add' => true,
                            'allow_delete' => true,
                            'by_reference' => false,
                        ));
                    } else {
                        $form->add($key, $formEntryType, array(
                            'label' => $key,
                            'data' => $currentValue,
                            'constraints' => $constraints
                        ));
                    }
                }
            }
        });
    }

}