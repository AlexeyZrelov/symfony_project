<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{

    private UserPasswordHasherInterface $password_encoder;

    public function __construct(UserPasswordHasherInterface $password_encoder)
    {
        $this->password_encoder = $password_encoder;
    }

    public function load(ObjectManager $manager): void
    {

        foreach ($this->getUserData() as [$name, $last_name, $email, $password, $api_key, $roles]) {

            $user = new User();
            $user->setName($name);
            $user->setLastName($last_name);
            $user->setEmail($email);
            $user->setPassword($this->password_encoder->hashPassword($user, $password));
            $user->setVimeoApiKey($api_key);
            $user->setRoles($roles);
            $manager->persist($user);
        }

        $manager->flush();
    }

    private function getUserData(): array
    {
        return [
            ['John', 'Wayne', 'jw@simf4.loc', 'passw', 'hjd8dehdh', ['ROLE_ADMIN']],
            ['John', 'Wayne2', 'jw2@simf4.loc', 'passw', null, ['ROLE_ADMIN']],
            ['John', 'Doe', 'jd@simf4.loc', 'passw', null, ['ROLE_USER']],
            ['Ted', 'Bundy', 'tb@simf4.loc', 'passw', null, ['ROLE_USER']]
        ];
    }

}
