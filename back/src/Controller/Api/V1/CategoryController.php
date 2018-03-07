<?php

namespace App\Controller\Api\V1;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends Controller
{
    /**
     * @Route("/categories", name="blog_list")
     */
    public function index()
    {
        $categories = $this->getDoctrine()
            ->getRepository(\App\Entity\Category::class)
            ->findAll();

        return new Response($this->get('7cart.serializer')->serialize($categories));
    }
}
