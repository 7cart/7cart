<?php

namespace App\Controller\Api\V1;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class AttributeController extends Controller
{
    /**
     * @Route("/attributes", name="attribute_list")
     */
    public function index()
    {
        $attributes = $this->getDoctrine()
            ->getRepository(\App\Entity\Attribute::class)
            ->findAll();

        return new Response($this->get('7cart.serializer')->serialize($attributes));
    }
}
