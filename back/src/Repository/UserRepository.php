<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;
use App\Service\OAuth2ClientWrapper;
class UserRepository extends EntityRepository
{

    public function findUserByOAuth(OAuth2ClientWrapper $client)
    {
        $user = null;
        $em = $this->getEntityManager();
        $providerName =  $client->getProviderName();
        $oAuthObject = $client->oAuthObject();
        $oAuth = $em->getRepository(\App\Entity\UserOAuth::class)
           ->findOneBy(['providerName' => $providerName, 'providerId' => $oAuthObject->getId()]);

        if ($oAuth) {
            $user = $this->findOneBy([
                'id' => $oAuth->getUserId()
            ]);
        }

        return $user;
    }

    public function findOrCreateUserByOAuth(OAuth2ClientWrapper $client)
    {

        $user = $this->findUserByOAuth($client);
        $em = $this->getEntityManager();
        $providerName =  $client->getProviderName();
        $oAuthObject = $client->oAuthObject();

        //create or join oauth to user
        if (!$user) {
            if ($oAuthObject) {
                $userEmail = $oAuthObject->getEmail();
                $user = $this->findOneBy([
                    'emailCanonical' => $userEmail
                ]);

                if (!$user) {
                    //create new user
                    $user = new \App\Entity\User();
                    if ($userEmail) {
                        //username will the same
                        $user->setEmail($userEmail);
                    } else {
                        $user->setUsername($providerName.'_'.$oAuthObject->getId().'_'.time());
                    }

                    if ($oAuthObject->getFirstName()) {
                        $user->setName($oAuthObject->getFirstName().' '.$oAuthObject->getLastName());
                    }

                    $user->setPlainPassword($this->randomStr(14));
                    $user->setEnabled(true);
                    $em->persist($user);
                    $em->flush();
                }

                // join provider to user
                $provider = new \App\Entity\UserOAuth();
                $provider->setUserId($user->getId());
                $provider->setProviderName($providerName);
                $provider->setProviderId($oAuthObject->getId());
                $em->persist($provider);
                $em->flush();
            }
        }

        return $user;
    }

    public function randomStr($length) {
        $str = '';
        $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $max = strlen($keyspace) - 1;

        for ($i = 0; $i < $length; ++$i) {
            $str .= $keyspace[random_int(0, $max)];
        }

        return $str;
    }

}