<?php

namespace App\Controller\Api\V1;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\Serializer;

class AttributeController extends AbstractController
{
    /**
     * @Route("/attributes", name="attribute_list", methods={"GET"})
     */
    public function index(Serializer $serializer)
    {
        $attributes = $this->getDoctrine()
            ->getRepository(\App\Entity\Attribute::class)
            ->findBy(['isActive' => true]);

        return new Response($serializer->serialize($attributes));
    }
}
