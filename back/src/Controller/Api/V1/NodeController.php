<?php

namespace App\Controller\Api\V1;

use App\Service\Filter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use \App\Entity\Node;

class NodeController extends Controller
{
    private $filterService;

    public function __construct(Filter $sf)
    {
        $this->filterService = $sf;
    }

    /**
     * @Route("/nodes", name="node_list")
     */
    public function index(Request $request)
    {
        $catId = $request->get('category_id', 0);
        $filters = $this->filterService->selectFiltersByString($request->get('f', ''));
        $allActiveAttr = $this->filterService->getAllActiveAttributesFromCategory($catId);

        $nodes = $this->getDoctrine()
            ->getRepository( Node::class)
            ->findNodesByCategory($catId, $filters);

        $counter = $this->getDoctrine()
            ->getRepository( Node::class)
            ->countNodesByCategory($catId, $filters, $allActiveAttr);

        return new Response($this->get('7cart.serializer')->serialize($nodes,
            ['filter-counter' => $counter,
              'attributes' => json_decode($this->get('7cart.serializer')->serialize($allActiveAttr))]));
    }
}
