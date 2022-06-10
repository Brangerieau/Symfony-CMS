<?php

namespace Brangerieau\SymfonyCmsBundle\DataFixtures;

use App\Entity\Menu;
use App\Entity\MenuItems;
use App\Repository\MenuItemsRepository;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\String\Slugger\SluggerInterface;

class MenuFixtures
{
    public function __construct(
        protected MenuItemsRepository $menuItemsRepository,
        protected SluggerInterface $slugger
    ){}

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('en_EN');

        $menu = New Menu();
        $menu->setName($faker->company())
            ->setSlug(strtolower($this->slugger->slug($menu->getName())));

        $manager->persist($menu);

        $firstItem = null;
        for($m = 0; $m < 10; $m++){
            $subMenu = new MenuItems();
            $subMenu->setMenu($menu)
                ->setText($faker->company())
                ->setLink('#')
                ->setOrders(rand(0, 100));

            if($m === 0){
                $firstItem = $subMenu;
            }

            if (rand(0, 1) === 1 && $m !== 0) {
                $subMenu->setParent($firstItem);
            }

            $manager->persist($subMenu);
        }

        $manager->flush();
    }
}