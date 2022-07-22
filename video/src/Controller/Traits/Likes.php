<?php

namespace App\Controller\Traits;

use App\Entity\User;

trait Likes {

    private function likeVideo($video): string
    {
        $user = $this->doctrine->getRepository(User::class)->find($this->getUser());
        $user->addLikedVideo($video);

        $em = $this->doctrine->getManager();
        $em->persist($user);
        $em->flush();

        return 'liked';
    }


    private function undoLikeVideo($video): string
    {
        $user = $this->doctrine->getRepository(User::class)->find($this->getUser());
        $user->removeLikedVideo($video);

        $em = $this->doctrine->getManager();
        $em->persist($user);
        $em->flush();

        return 'undo liked';
    }

    private function undoDislikeVideo($video): string
    {
        $user = $this->doctrine->getRepository(User::class)->find($this->getUser());
        $user->removeDislikedVideo($video);

        $em = $this->doctrine->getManager();
        $em->persist($user);
        $em->flush();

        return 'undo disliked';
    }

}