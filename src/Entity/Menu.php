<?php

namespace Brangerieau\SymfonyCmsBundle\Entity;

use Brangerieau\SymfonyCmsBundle\Repository\MenuRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MenuRepository::class)]
class Menu
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $name;

    #[ORM\Column(type: 'string', length: 255)]
    private string $slug;

    #[ORM\OneToMany(mappedBy: 'menu', targetEntity: MenuItems::class, orphanRemoval: true)]
    private ArrayCollection|Collection $menuItems;

    public function __construct()
    {
        $this->menuItems = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return Collection<int, MenuItems>
     */
    public function getMenuItems(): Collection
    {
        foreach ($this->menuItems as $key => $menuItem) {
            if (null !== $menuItem->getParent()) {
                $this->menuItems->remove($key);
            }
        }

        return $this->menuItems;
    }

    /**
     * @return Collection<int, MenuItems>
     */
    public function getAllMenuItems(): Collection
    {
        return $this->menuItems;
    }

    public function addMenuItem(MenuItems $menuItem): self
    {
        if (!$this->menuItems->contains($menuItem)) {
            $this->menuItems[] = $menuItem;
            $menuItem->setMenu($this);
        }

        return $this;
    }

    public function removeMenuItem(MenuItems $menuItem): self
    {
        if ($this->menuItems->removeElement($menuItem)) {
            // set the owning side to null (unless already changed)
            if ($menuItem->getMenu() === $this) {
                $menuItem->setMenu(null);
            }
        }

        return $this;
    }
}
