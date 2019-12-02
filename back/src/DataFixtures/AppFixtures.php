<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    private $_userManager;
    private $_clientManager;

    public function __construct(\FOS\UserBundle\Model\UserManagerInterface $um,
                                 \FOS\OAuthServerBundle\Model\ClientManagerInterface $cm)
    {
        $this->_userManager = $um;
        $this->_clientManager = $cm;
    }

    public function load(ObjectManager $manager)
    {

        // Create our user and set details
        $user = $this->_userManager->createUser();
        $user->setUsername('admin');
        $user->setEmail('admin@admin.com');
        $user->setPlainPassword('admin');
        $user->setEnabled(true);
        $user->setRoles(array('ROLE_ADMIN'));

        // Update the user
        $this->_userManager->updateUser($user, true);

        //create Oauth local client
        $client = $this->_clientManager->createClient();
        $client->setRandomId('673n1g24ei8884w8wg4wcwwg4gkw488k0gg0wgoskscsc4sgk4');
        $client->setSecret('shsed4mdzj4g0kkk84c8s4ogkkg08gg4kkskkw4skocc48g4o');
        $client->setAllowedGrantTypes(array('password'));
        $this->_clientManager->updateClient($client);

        $manager->flush();
    }
}
