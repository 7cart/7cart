<?php

namespace App\Controller;

use EasyCorp\Bundle\EasyAdminBundle\Controller\AdminController as BaseAdminController;
use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class AdminController extends BaseAdminController
{
    protected function createEntityFormBuilder($entity, $view)
    {

        if ($entity instanceof \App\Entity\Node) {
            $nodeCat = $this->em->getRepository(Category::class)->findBy(['id' => $entity->getCategoriesId()]);
            $entity->setCategories($nodeCat);
        }

        return parent::createEntityFormBuilder($entity, $view);
    }

    public function createAttributeValueEntityFormBuilder($entity, $view)
    {
        $formBuilder = parent::createEntityFormBuilder($entity, $view);

        if ($formBuilder->has('attribute')) {
            //@TODO add marge options and constants for select, multiSelect
            // to keep the current options
            //$options = $formBuilder->get('attribute')->getFormConfig()->getOptions();
            // add/change the options here
            $options['class'] = 'App\Entity\Attribute';
            $options['query_builder'] = function (\Doctrine\ORM\EntityRepository $er) {
                return $er->createQueryBuilder('attr')
                    ->where('attr.inputType = :select')
                    ->orWhere('attr.inputType = :multiSelect')
                    ->setParameter('select', "select")
                    ->setParameter('multiSelect', "multiSelect");
            };
            // re-define the field options
            $formBuilder->add('attribute', EntityType::class, $options);
       }

        return  $formBuilder;
    }
}