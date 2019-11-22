<?php

namespace App\Service;


use FOS\UserBundle\Mailer\Mailer;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class FOSCustomizedMailer extends Mailer
{

    protected $frontHost;

    public function __construct($mailer, UrlGeneratorInterface  $router, EngineInterface $templating, array $parameters, string $frontHost)
    {
        $this->mailer = $mailer;
        $this->router = $router;
        $this->templating = $templating;
        $this->parameters = $parameters;
        $this->frontHost = $frontHost ;
    }

    public function sendResettingEmailMessage(UserInterface $user)
    {
        $template = $this->parameters['resetting.template'];
        $url = $this->frontHost.'/change-password/'. $user->getConfirmationToken();
        $rendered = $this->templating->render($template, array(
            'user' => $user,
            'confirmationUrl' => $url,
        ));
        $this->sendEmailMessage($rendered, $this->parameters['from_email']['resetting'], (string) $user->getEmail());
    }

}
