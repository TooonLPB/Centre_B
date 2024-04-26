<?php

namespace App\DataFixtures;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setEmail('admin@test.fr');
        $user->setRoles(['ROLE_ADMIN']);
        $user->setNom('Admin');
        $user->setPrenom('Admin');
        $password = $this->hasher->hashPassword($user, 'Password');
        $user->setPassword($password);

        $manager->persist($user);
        $manager->flush();
    }
}
