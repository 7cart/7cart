<?php

namespace App\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="user_oauth")
 * @ORM\HasLifecycleCallbacks
 */
class UserOAuth
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="integer")
     */
    protected $userId;

    /**
     * @ORM\Column(type="string")
     */
    protected $providerId;

    /**
     * @ORM\Column(type="string")
     */
    protected $providerName;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param mixed $userId
     */
    public function setUserId($userId): void
    {
        $this->userId = $userId;
    }

    /**
     * @return mixed
     */
    public function getProviderId()
    {
        return $this->providerId;
    }

    /**
     * @param mixed $providerId
     */
    public function setProviderId($providerId): void
    {
        $this->providerId = $providerId;
    }

    /**
     * @return mixed
     */
    public function getProviderName()
    {
        return $this->providerName;
    }

    /**
     * @param mixed $providerName
     */
    public function setProviderName($providerName): void
    {
        $this->providerName = $providerName;
    }

}
