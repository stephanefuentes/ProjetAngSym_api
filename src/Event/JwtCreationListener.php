<?php


namespace App\Event;


use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;



class JwtCreationListener
{


    public function injectDataIntoJwt(JWTCreatedEvent $event)
    {
        // 3. Récupérer les données du token (propriété data dans $event)
        $data = $event->getData();
        // 4. Mettre l'avatar dans les données
        $data['avatar'] = $event->getUser()->getAvatar();
        // 5. Remettre les nouvelles données dans $event
        $event->setData($data);
    }







    // public function injectDataIntoJwt(JWTCreatedEvent $event)
    // {
    //     // 1. Récuperer l'utilisateur ( propriété user)
    //    $user = $event->getUser();

    //    $avatar = $event->getAvatar();

    //    $data = $event->getData();

    //    $data['avatar'] = $avatar;

    //    $event->setData($data);


    // }

}