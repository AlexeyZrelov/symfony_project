<?php

namespace App\Listeners;

use App\Entity\User;
use App\Entity\Video;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class NewVideoListener
{

//    public function postPersist(LifecycleEventArgs $args)
//    {
//        $entity = $args->getObject();
//
//        if (!$entity instanceof Video) {
//            return;
//        }
//
//        $entityManager = $args->getObjectManager();
//
//        $users = $entityManager->getRepository(User::class)->findAll();
//
//        foreach ($users as $user) {
//
//            exit($user->getName().' '.$entity->getTitle());
//
//        }
//
//    }

}