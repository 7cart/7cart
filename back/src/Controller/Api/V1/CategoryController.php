<?php

namespace App\Controller\Api\V1;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\Serializer;

class CategoryController extends AbstractController
{
    /**
     * @Route("/categories", name="category_list", methods={"GET"})
     */
    public function list(Serializer $serializer)
    {
        $categories = $this->getDoctrine()
            ->getRepository(\App\Entity\Category::class)
            ->findAll();

        return new Response($serializer->serialize($categories));
    }

    /**
     * @Route("/categories/{id}", name="category_show", methods={"GET"})
     */
    public function show($id, Serializer $serializer)
    {
        $category = $this->getDoctrine()
            ->getRepository(\App\Entity\Category::class)
            ->findOneBy(['id' => $id]);

        return new Response($serializer->serialize($category));
    }
}
