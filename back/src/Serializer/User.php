<?php

namespace App\Serializer;

use \Neomerx\JsonApi\Schema\BaseSchema;

class User extends BaseSchema
{

    protected $resourceType = 'users';

    public function getId($data): ?string
    {
        return $data->getId();
    }

    public function getAttributes($data, array $fieldKeysFilter = null): ?array
    {
        //@TODO: rewrite global variable
        global $kernel;
        $user = $kernel->getContainer()->get('security.token_storage')->getToken()->getUser();
        $baseUrl = $_ENV['FRONT_HOST'];

        $res = [
            'name' => $data->getName(),
            'avatar-url' => $baseUrl.$kernel->getContainer()->get('vich_uploader.templating.helper.uploader_helper')->asset($data, 'avatarFile')
        ];

        if (\is_object($user) && $user->getId() == $data->getId()) {
            if (false === $kernel->getContainer()->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
                throw new AccessDeniedException();
            }

            $res['email'] = $data->getEmail();
        }

        return $res;
    }

}