<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Request;
use League\OAuth2\Client\Provider\ResourceOwnerInterface;

class OAuth2ClientWrapper
{

    private $_client;

    private $_providerName;

    private $_providerData;

    public function __construct(string $providerName,  \KnpU\OAuth2ClientBundle\Client\OAuth2Client $client)
    {
        $client->setAsStateless();
        $this->_client = $client;
        $this->_providerName = $providerName;
        $this->_providerData = $client->fetchUser();
    }

    public function  getClient() {
        return $this->_client;
    }

    public function  getProviderName() {
        return $this->_providerName;
    }

    public function  oAuthObject() : ResourceOwnerInterface {
        return $this->_providerData;
    }

}

