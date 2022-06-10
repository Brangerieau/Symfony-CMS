<?php

namespace Brangerieau\SymfonyCmsBundle\DataFixtures;

use Brangerieau\SymfonyCmsBundle\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct(
        protected UserPasswordHasherInterface $hasher
    ){}

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('en_EN');

        $user = New User();
        $user->setEmail('admin@symfony-cms.fr')
            ->setPassword($this->hasher->hashPassword($user, 'admin'))
            ->setRoles(['ROLE_ADMIN']);

        $manager->persist($user);

        for ($u = 0; $u < 5; $u++){
            $user = New User();
            $user->setEmail($faker->email())
                ->setPassword($this->hasher->hashPassword($user, 'password'));

            $manager->persist($user);
        }

        $manager->flush();
    }
}
