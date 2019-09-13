<?php

namespace App\Event;


use App\Entity\User;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class PasswordEncodingListener implements EventSubscriberInterface
{


    private $encoder;


    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }



    public static function getSubscribedEvents()
    {
        return [
            // 3000 représente la priorité
            "kernel.request" => ["convertUserPassword", 0]

        ];
    }



    public function convertUserPassword(RequestEvent $event)
    {

        $data = $event->getRequest()->attributes->get('data');
        $method = $event->getRequest()->getMethod();

        if($data && $data instanceof User && $method === "POST" && $data->getPassword() !== "")
        {
            $plainPassword = $data->getPassword();
            $hash = $this->encoder->encodePassword($data, $plainPassword);
            //dd($hash);

            $data->setPassword($hash);
        }
           // dd($event->getRequest());
        
    }

}