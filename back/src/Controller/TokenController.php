<?php

namespace App\Controller;

use FOS\OAuthServerBundle\Controller\TokenController as BaseTokenController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use OAuth2\OAuth2ServerException;
use App\Service\OAuth2ClientWrapper;

class TokenController extends BaseTokenController implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    public function tokenAndSocialAction(Request $request) {
        $data = json_decode($request->request->get('password'), true);

        if (is_array($data)) {
            $innerClient = $this->_getInnerClient($request);
            $request->attributes->set('code', $data['authorizationCode'] ?? '');
            $providerName = $data['provider'] ?? '';
            $client = new OAuth2ClientWrapper($providerName, $this->container->get('knpu.oauth2.registry')->getClient($providerName));

            $user = $this->container->get('doctrine')->getRepository(\App\Entity\User::class)
                ->findOrCreateUserByOAuth($client);

            if ($innerClient && $user) {
                $token = $this->server->createAccessToken($innerClient, $user);
                return new Response(json_encode($token), 200);
            } else {
                throw new OAuth2ServerException(Response::HTTP_BAD_REQUEST, 'The client credentials are invalid');
            }
        } else {
            return $this->tokenAction($request);
        }
    }

    private function _getInnerClient(Request $request) {

        $publicId = $request->request->get('client_id');
        $secret = $request->request->get('client_secret');

        if (false === $pos = mb_strpos($publicId, '_')) {
            return null;
        }
        $id = mb_substr($publicId, 0, $pos);
        $randomId = mb_substr($publicId, $pos + 1);

        return $this->container->get('doctrine')->getRepository(\App\Entity\OAuth2\Client::class)
            ->findOneBy([
                'id' => $id,
                'randomId' => $randomId,
                'secret' => $secret
            ]);
    }
}