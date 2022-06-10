<?php

namespace Brangerieau\SymfonyCmsBundle\Entity;

use Brangerieau\SymfonyCmsBundle\Repository\MenuItemsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MenuItemsRepository::class)]
class MenuItems
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'childItems')]
    private ?MenuItems $parent;

    #[ORM\OneToMany(mappedBy: 'parent', targetEntity: self::class)]
    #[ORM\OrderBy(['orders' => 'asc'])]
    private ArrayCollection|Collection $childItems;

    #[ORM\Column(type: 'integer')]
    private int $orders;

    #[ORM\Column(type: 'string', length: 255)]
    private string $text;

    #[ORM\Column(type: 'string', length: 255)]
    private string $link;

    #[ORM\ManyToOne(targetEntity: Menu::class, inversedBy: 'menuItems')]
    #[ORM\JoinColumn(nullable: false)]
    private Menu $menu;

    public function __construct()
    {
        $this->childItems = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getChildItems(): Collection
    {
        return $this->childItems;
    }

    public function addChildItem(self $childItem): self
    {
        if (!$this->childItems->contains($childItem)) {
            $this->childItems[] = $childItem;
            $childItem->setParent($this);
        }

        return $this;
    }

    public function removeChildItem(self $childItem): self
    {
        if ($this->childItems->removeElement($childItem)) {
            // set the owning side to null (unless already changed)
            if ($childItem->getParent() === $this) {
                $childItem->setParent(null);
            }
        }

        return $this;
    }

    public function getOrders(): int
    {
        return $this->orders;
    }

    public function setOrders(int $orders): self
    {
        $this->orders = $orders;

        return $this;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getLink(): string
    {
        return $this->link;
    }

    public function setLink(string $link): self
    {
        $this->link = $link;

        return $this;
    }

    public function getMenu(): Menu
    {
        return $this->menu;
    }

    public function setMenu(Menu $menu): self
    {
        $this->menu = $menu;

        return $this;
    }
}
