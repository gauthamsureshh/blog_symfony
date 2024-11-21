<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $userPasswordHasher) {}
    public function load(ObjectManager $manager): void
    {
        $user1 = new User();
        $user1->setEmail('gautham@gmail.com');
        $user1->setUsername('gautham');
        $user1->setPassword(
            $this->userPasswordHasher->hashPassword($user1, '1234')
        );
        $manager->persist($user1);

        $user2 = new User();
        $user2->setEmail('alex@gmail.com');
        $user2->setUsername('alex');
        $user2->setPassword(
            $this->userPasswordHasher->hashPassword($user2, '123')
        );
        $manager->persist($user2);

        $manager->flush();
    }
}
