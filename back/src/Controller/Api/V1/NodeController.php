<?php

namespace App\Controller\Api\V1;

use App\Service\Filter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use \App\Entity\Node;
use App\Service\Serializer;

class NodeController extends AbstractController
{
    private $filterService;

    public function __construct(Filter $sf)
    {
        $this->filterService = $sf;
    }

    /**
     * @Route("/nodes", name="node_list", methods={"GET"})
     */
    public function list(Request $request, Serializer $serializer)
    {
        $catId = $request->get('category_id', 0);
        $pageNo = $request->get('page', 1);
        $perPage = abs($request->get('per_page', 10));
        $filters = $this->filterService->selectFiltersByString($request->get('f', ''));
        $allActiveAttr = $this->filterService->getAllActiveAttributesFromCategory($catId);

        $nodes = $this->getDoctrine()
            ->getRepository( Node::class)
            ->findNodesByCategory($catId, $filters, $pageNo, $perPage);

        $count = $this->getDoctrine()
            ->getRepository( Node::class)
            ->countNodesByCategory($catId, $filters);

        $meta = ['total_pages' => ceil($count/$perPage)];

        if (!$request->get('event')) {
            $meta['attributes'] = json_decode($serializer->serialize($allActiveAttr));
        }

        if (!$request->get('event') || $request->get('event') == 'filter') {
            $meta['filter-counter'] = $this->getDoctrine()
                ->getRepository(Node::class)
                ->countAttributesByCategory($catId, $filters, $allActiveAttr);
        }

        return new Response($serializer->serialize($nodes, $meta));
    }

    /**
     *
     * @Route("/nodes/{id}", name="node_show", methods={"GET"})
     */
    public function show($id, Serializer $serializer)
    {
        $node = $this->getDoctrine()
            ->getRepository( Node::class)
            ->findOneBy(['id' => $id]);

        $meta = [];
        $allActiveAttr = $this->filterService->selectActiveAttributesByName(array_keys($node->getAttributes()));
        if ($allActiveAttr) {
            $meta['attributes'] = json_decode($serializer->serialize($allActiveAttr));
        }

        return new Response($serializer->serialize($node, $meta));
    }

}
