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
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('en_EN');

        $user = new User();
        $user->setEmail('admin@symfony-cms.fr')
            ->setPassword($this->hasher->hashPassword($user, 'admin'))
            ->setLastname($faker->lastName())
            ->setFirstname($faker->firstName())
            ->setRoles(['ROLE_ADMIN']);

        $manager->persist($user);

        for ($u = 0; $u < 5; ++$u) {
            $user = new User();
            $user->setEmail($faker->email())
                ->setPassword($this->hasher->hashPassword($user, 'password'))
                ->setLastname($faker->lastName())
                ->setFirstname($faker->firstName());

            $manager->persist($user);
        }

        $manager->flush();
    }
}
