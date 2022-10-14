<?php

namespace Brangerieau\SymfonyCmsBundle\DataFixtures;

use Brangerieau\SymfonyCmsBundle\Entity\Pages;
use Brangerieau\SymfonyCmsBundle\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\String\Slugger\SluggerInterface;

class PagesFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(
        protected SluggerInterface $slugger,
        protected UserRepository $userRepository
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('en_EN');

        $page = new Pages();
        $page->setName('Home')
            ->setSlug(strtolower($this->slugger->slug($page->getName())))
            ->setAuthor(
                $this->userRepository->findOneBy(['email' => 'superadmin@symfony-cms.fr'])
            )
            ->setVisible(1)
            ->setModifiedAt(new \DateTimeImmutable())
            ->setCreatedAt(new \DateTimeImmutable())
            ->setContent('[]')
            ->setHome(true);

        $manager->persist($page);

        for ($u = 0; $u < 15; ++$u) {
            $page = new Pages();
            $page->setName($faker->text(20))
                ->setSlug(strtolower($this->slugger->slug($page->getName())))
                ->setAuthor(
                    $this->userRepository->findOneBy(['email' => 'superadmin@symfony-cms.fr'])
                )
                ->setVisible(rand(0, 1))
                ->setModifiedAt(new \DateTimeImmutable())
                ->setCreatedAt(new \DateTimeImmutable())
                ->setContent('[]')
                ->setShortContent($faker->text(50))
                ->setMetaDescription($faker->text(70));

            $manager->persist($page);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }
}
