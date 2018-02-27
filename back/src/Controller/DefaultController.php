<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller
{
    /**
     *
     * @Route("/", name="blog_list")
     */
    public function index()
    {
        return new Response(
            '<html><body>Hello!</body></html>'
        );
    }
}
