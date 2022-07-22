<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\User;
use App\Entity\Video;
use App\DataFixtures\UserFixtures;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CommentFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        foreach ($this->CommentData() as [$content, $user, $video, $created_at]) {

            $comment = new Comment();
            $user = $manager->getRepository(User::class)->find($user);
            $video = $manager->getRepository(Video::class)->find($video);

            $comment->setContent($content);
            $comment->setUser($user);
            $comment->setVideo($video);
            $comment->setCreatedAtForFixtures(new \DateTime($created_at));

            $manager->persist($comment);

        }

        $manager->flush();
    }

    private function CommentData()
    {
        return [
            ['Comment 1 Cras sit amet nibh libero, in gravida nulla. Nulla velmetus scelerisque ante sollicitudein. Cras purus odio, vestibulum in vulputate at, tempus viverra turpis. Fusce condimentum nunc acnisi vulputate fringilla. Donec lacinia conque felis in faucibus.',1,10,'2018-10-08 12:34:45'],
            ['Comment 1 Cras sit amet nibh libero, in gravida nulla. Nulla velmetus scelerisque ante sollicitudein. Cras purus odio, vestibulum in vulputate at, tempus viverra turpis. Fusce condimentum nunc acnisi vulputate fringilla. Donec lacinia conque felis in faucibus.',2,10,'2018-09-08 10:34:45'],
            ['Comment 1 Cras sit amet nibh libero, in gravida nulla. Nulla velmetus scelerisque ante sollicitudein. Cras purus odio, vestibulum in vulputate at, tempus viverra turpis. Fusce condimentum nunc acnisi vulputate fringilla. Donec lacinia conque felis in faucibus.',3,10,'2018-08-08 23:34:45'],
            ['Comment 1 Cras sit amet nibh libero, in gravida nulla. Nulla velmetus scelerisque ante sollicitudein. Cras purus odio, vestibulum in vulputate at, tempus viverra turpis. Fusce condimentum nunc acnisi vulputate fringilla. Donec lacinia conque felis in faucibus.',1,11,'2018-10-08 11:23:34'],
            ['Comment 1 Cras sit amet nibh libero, in gravida nulla. Nulla velmetus scelerisque ante sollicitudein. Cras purus odio, vestibulum in vulputate at, tempus viverra turpis. Fusce condimentum nunc acnisi vulputate fringilla. Donec lacinia conque felis in faucibus.',2,11,'2018-09-08 15:17:06'],
            ['Comment 1 Cras sit amet nibh libero, in gravida nulla. Nulla velmetus scelerisque ante sollicitudein. Cras purus odio, vestibulum in vulputate at, tempus viverra turpis. Fusce condimentum nunc acnisi vulputate fringilla. Donec lacinia conque felis in faucibus.',3,11,'2018-08-08 21:34:45'],
            ['Comment 1 Cras sit amet nibh libero, in gravida nulla. Nulla velmetus scelerisque ante sollicitudein. Cras purus odio, vestibulum in vulputate at, tempus viverra turpis. Fusce condimentum nunc acnisi vulputate fringilla. Donec lacinia conque felis in faucibus.',3,11,'2018-08-08 22:34:45'],
            ['Comment 1 Cras sit amet nibh libero, in gravida nulla. Nulla velmetus scelerisque ante sollicitudein. Cras purus odio, vestibulum in vulputate at, tempus viverra turpis. Fusce condimentum nunc acnisi vulputate fringilla. Donec lacinia conque felis in faucibus.',3,11,'2018-08-08 23:34:45']
        ];
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class
        ];
    }
}
