<?php

namespace App\Controller\Api\V1;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class NodeController extends Controller
{
    /**
     * @Route("/nodes", name="node_list")
     */
    public function index(Request $request)
    {
        $nodes = $this->getDoctrine()
            ->getRepository( \App\Entity\Node::class)
            ->findNodesByCategory($request->get('category_id', 0), $request->get('f'))
            ->execute();

        return new Response($this->get('7cart.serializer')->serialize($nodes));
    }
}
