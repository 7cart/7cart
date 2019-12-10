<?php

namespace App\Controller\Api\V1\Authenticated;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\Serializer;
use App\Service\Deserializer;
use \FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserController extends AbstractController implements TokenAuthenticatedInterface
{

    public function index(Serializer $serializer)
    {
        return new Response($serializer->serialize($this->getUser()));
    }

    /**
     *
     * @Route("/users/{id}", name="user_edit", methods={"PATCH"})
     */
    public function edit($id, Request $request, Deserializer $deserializer, Serializer $serializer, ValidatorInterface $validator, UserManagerInterface $um)
    {
        $attr = $deserializer->deserializeRequestAttributes($request);

        /** @var User $user */
        $user = $this->getUser();
        $user->setName($attr['name']);
        if (!$user->getEmail()) {
            $user->setEmail($attr['email']);
        }

        $errors = $validator->validate($user);
        if (count($errors) > 0) {
            return new Response($serializer->serializeValidatorError($errors), 422);
        }

        $um->updateUser($user);
        return new Response($serializer->serialize($user));
    }

    /**
     * @Route("/users/upload", name="upload_avatar", methods={"POST"})
     *
     */
    public function downloadAvatarAction(Request $request, Serializer $serializer, UserManagerInterface $um)
    {
        $user = $this->getUser();
        $user->setAvatarFile($request->files->get('file'));
        $um->updateUser($user);
        return new Response($serializer->serialize($user));
    }

}
