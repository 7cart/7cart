<?php

namespace App\Controller;

use EasyCorp\Bundle\EasyAdminBundle\Controller\AdminController as BaseAdminController;
use App\Entity\Category;


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
}