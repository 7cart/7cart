<?php

namespace App\Controller\Api\V1;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use FOS\UserBundle\Form\Factory\FormFactory;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use App\Service\Serializer;
use App\Service\Deserializer;
use Symfony\Contracts\Translation\TranslatorInterface;
use \FOS\UserBundle\Model\UserManagerInterface;

class UserController extends AbstractController
{
    private $_formRegistrationFactory;
    private $_formResettingFactory;
    private $_mailer;
    private $_tokenGenerator;
    private $_serializer;

    public function __construct(FormFactory $formRegistrationFactory,
                                FormFactory $formResettingFactory,
                                $tokenGenerator,
                                $mailer,
                                Serializer $serializer)
    {
        $this->_formRegistrationFactory = $formRegistrationFactory;
        $this->_formResettingFactory = $formResettingFactory;
        $this->_tokenGenerator = $tokenGenerator;
        $this->_mailer = $mailer;
        $this->_serializer = $serializer;
    }

    /**
     *
     * @Route("/users", name="users_index", methods={"GET"})
     */
    public function index(Request $request)
    {
        if ($request->query->get('me')) {
            return $this->forward('App\Controller\Api\V1\Authenticated\UserController::index');
        } else {
            $users = $this->getDoctrine()
                ->getRepository(User::class)
                ->findAll();

            return new Response($this->_serializer->serialize($users));
        }
    }

    /**
     *
     * @Route("/users", name="users_registration", methods={"POST"})
     */
    public function registration(Request $request, Deserializer $deserializer, UserManagerInterface $um)
    {
        $form = $this->_formRegistrationFactory->createForm(array('csrf_protection' => false, 'allow_extra_fields' => true));
        $attr = $deserializer->deserializeRequestAttributes($request);

        $form->submit($attr);
        if ($form->isValid()) {
            $user = $form->getData();
            $user->setEnabled(true);
            $um->updateUser($user);
            return new Response($this->_serializer->serialize($user));
        } else {
            return new Response($this->_serializer->serializeFormError($form), 422);
        }
    }

    /**
     *
     * @Route("/users/reset", name="users_reset_password", methods={"POST"})
     */
    public function resetPassword(Request $request, TranslatorInterface $translator, UserManagerInterface $um)
    {
        $email = $request->request->get('email');
        $ttl = $this->getParameter('fos_user.resetting.retry_ttl');
        $msg = $translator->trans('resetting.check_email', ['%tokenLifetime%' => ceil($ttl / 3600)], 'FOSUserBundle');
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
    public function changePassword(Request $request, TranslatorInterface $translator, UserManagerInterface $um)
    {

        $ttl = $this->getParameter('fos_user.resetting.token_ttl');
        $newPassword = $request->request->get('password');
        $user = $um->findUserByConfirmationToken($request->request->get('token'));

        if (null === $user) {
            throw $this->createNotFoundException($translator->trans('user.error', [], 'application'));
        }

        if (!$user->isPasswordRequestNonExpired($ttl)) {
            throw new BadRequestHttpException('token invalidate');
        }

        $form = $this->_formResettingFactory->createForm(array('csrf_protection' => false));
        $attr['plainPassword'] = ['first' => $newPassword, 'second' => $newPassword];
        $form->setData($user);
        $form->submit($attr);
        if ($form->isValid()) {
            $user->setConfirmationToken(null);
            $user->setPasswordRequestedAt(null);
            $user = $form->getData();
            $um->updateUser($user);
            return new Response($this->_serializer->serialize($user));
        } else {
            return new Response($this->_serializer->serializeFormError($form), 422);
        }
    }
}
