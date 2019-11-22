<?php

namespace App\Controller\Api\V1;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use FOS\UserBundle\Form\Factory\FormFactory;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;


class UserController extends Controller
{
    private $_formRegistrationFactory;
    private $_formResettingFactory;
    private $_mailer;
    private $_tokenGenerator;

    public function __construct(FormFactory $formRegistrationFactory, FormFactory $formResettingFactory, $tokenGenerator, $mailer )
    {
        $this->_formRegistrationFactory = $formRegistrationFactory;
        $this->_formResettingFactory = $formResettingFactory;
        $this->_tokenGenerator = $tokenGenerator;
        $this->_mailer = $mailer;
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

    /**
     *
     * @Route("/users/reset", name="users_reset_password", methods={"POST"})
     */
    public function resetPassword(Request $request)
    {
        $email = $request->request->get('email');
        $ttl = $this->container->getParameter('fos_user.resetting.retry_ttl');
        $translator = $this->get('translator');
        $msg = $translator->trans('resetting.check_email', ['%tokenLifetime%' => ceil($ttl / 3600)], 'FOSUserBundle');
        $um = $this->get('fos_user.user_manager');
        $user = $um->findUserByEmail($email);

        if (null === $user) {
            throw $this->createNotFoundException($translator->trans('user.not_found', [], 'application'));
        }

        if ($user->isPasswordRequestNonExpired($ttl)) {
             throw new BadRequestHttpException($msg);
        }

        if (empty($user->getConfirmationToken())) {
            $user->setConfirmationToken($this->_tokenGenerator->generateToken());
        }

        $this->_mailer->sendResettingEmailMessage($user);
        $user->setPasswordRequestedAt(new \DateTime());
        $um->updateUser($user);

        return new JsonResponse($msg);
    }

    /**
     *
     * @Route("/users/change", name="users_change_password", methods={"POST"})
     */
    public function changePassword(Request $request)
    {
        $um = $this->get('fos_user.user_manager');
        $translator = $this->get('translator');
        $ttl = $this->container->getParameter('fos_user.resetting.token_ttl');
        $newPassword = $request->request->get('password');
        $user = $um->findUserByConfirmationToken($request->request->get('token'));

        if (null === $user) {
            throw $this->createNotFoundException($translator->trans('user.error', [], 'application'));
        }

        if (!$user->isPasswordRequestNonExpired($ttl)) {
            throw new BadRequestHttpException('token invalidate');
        }

        $form = $this->_formResettingFactory->createForm(array('csrf_protection' => false));
        $attr['plainPassword'] =  ['first' => $newPassword, 'second' => $newPassword];
        $form->setData($user);
        $form->submit($attr);
        if ($form->isValid()) {
            $user->setConfirmationToken(null);
            $user->setPasswordRequestedAt(null);
            $user = $form->getData();
            $um->updateUser($user);
            return new Response($this->get('7cart.serializer')->serialize($user));
        } else {
            return new Response($this->get('7cart.serializer')->serializeFormError($form), 422);
        }
    }

}
