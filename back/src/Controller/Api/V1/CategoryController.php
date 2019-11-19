<?php

namespace App\Controller\Api\V1;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends Controller
{
    /**
     * @Route("/categories", name="category_list", methods={"GET"})
     */
    public function list()
    {
        $categories = $this->getDoctrine()
            ->getRepository(\App\Entity\Category::class)
            ->findAll();

        return new Response($this->get('7cart.serializer')->serialize($categories));
    }

    /**
     * @Route("/categories/{id}", name="category_show", methods={"GET"})
     */
    public function show($id)
    {
        $category = $this->getDoctrine()
            ->getRepository(\App\Entity\Category::class)
            ->findOneBy(['id' => $id]);

        return new Response($this->get('7cart.serializer')->serialize($category));
    }
}
