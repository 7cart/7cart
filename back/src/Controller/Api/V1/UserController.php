<?php

namespace App\Controller\Api\V1;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;


class UserController extends Controller
{
    private $_formRegistrationFactory;

    public function __construct(FormFactory $formRegistrationFactory)
    {
        $this->_formRegistrationFactory = $formRegistrationFactory;
    }

    /**
     *
     * @Route("/users", name="users_index", methods={"GET"})
     */
    public function index()
    {

        if (false === $this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw new AccessDeniedException();
        }
        return new Response($this->get('7cart.serializer')->serialize($this->getUser()));
    }

    /**
     *
     * @Route("/users", name="users_registration", methods={"POST"})
     */
    public function registration(Request $request)
    {
        $form = $this->_formRegistrationFactory->createForm(array('csrf_protection' => false));
        $attr = $this->get('7cart.deserializer')->deserializeRequestAttributes($request);

        $form->submit($attr);
        if ($form->isValid()) {
            $user = $form->getData();
            $user->setEnabled(true);
            $userManager = $this->get('fos_user.user_manager');
            $userManager->updateUser($user);
            return new Response($this->get('7cart.serializer')->serialize($user));
        } else {
            return new Response($this->get('7cart.serializer')->serializeFormError($form), 422);
        }
    }

}
