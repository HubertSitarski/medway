<?php

namespace App\Entity;

use App\Repository\CartRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CartRepository::class)
 */
class Cart extends BaseEntity
{
    /**
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="cart")
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=ProductCart::class, mappedBy="cart")
     */
    private $productCarts;

    /**
     * @ORM\Column(name="session_id", type="string", nullable=true)
     */
    private $session;

    public function __construct()
    {
        $this->productCarts = new ArrayCollection();
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection|ProductCart[]
     */
    public function getProductCarts(): Collection
    {
        return $this->productCarts;
    }

    public function addProductCart(ProductCart $productCart): self
    {
        if (!$this->productCarts->contains($productCart)) {
            $this->productCarts[] = $productCart;
            $productCart->setCart($this);
        }

        return $this;
    }

    public function removeProductCart(ProductCart $productCart): self
    {
        if ($this->productCarts->contains($productCart)) {
            $this->productCarts->removeElement($productCart);
            // set the owning side to null (unless already changed)
            if ($productCart->getCart() === $this) {
                $productCart->setCart(null);
            }
        }

        return $this;
    }

    public function getSession(): ?string
    {
        return $this->session;
    }

    public function setSession(?string $session): self
    {
        $this->session = $session;

        return $this;
    }

    public function getFullPrice(): float
    {
        $summary = 0.0;

        /** @var ProductCart $productCart */
        foreach ($this->productCarts as $productCart) {
            $summary += $productCart->getProduct()->getPrice();
        }

        return $summary;
    }
}
