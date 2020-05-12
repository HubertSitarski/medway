<?php

namespace App\Service;

use App\Entity\Cart;
use App\Entity\ProductCart;
use App\Entity\User;
use App\Repository\CartRepository;
use App\Repository\ProductCartRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class CartService
{
    private $cartRepository;
    private $productRepository;
    private $entityManager;
    private $productCartRepository;

    public function __construct(
        CartRepository $cartRepository,
        ProductRepository $productRepository,
        EntityManagerInterface $entityManager,
        ProductCartRepository $productCartRepository
    ) {
        $this->cartRepository = $cartRepository;
        $this->productRepository = $productRepository;
        $this->entityManager = $entityManager;
        $this->productCartRepository = $productCartRepository;
    }

    public function findCart(?string $sessionId, ?UserInterface $user)
    {
        if ($user) {
            $cart = $this
                    ->cartRepository
                    ->findOneBy(['user' => $user]) ?? $this->createCart(null, $user)
            ;
        } elseif ($user) {
            $cart = $this
                    ->cartRepository
                    ->findOneBy(['session' => $sessionId]) ?? $this->createCart($sessionId, null)
            ;
        } else {
            $cart = null;
        }

        return $cart;
    }

    public function addProductCart(Cart $cart, int $id): Cart
    {
        $productCart = new ProductCart();
        $product = $this->productRepository->findOneBy(['id' => $id]);
        $productCart
            ->setQuantity(1)
            ->setPrice($product->getPrice())
            ->setProduct($product)
        ;

        $cart->addProductCart($productCart);

        $this->entityManager->persist($productCart);
        $this->entityManager->flush();

        return $cart;
    }

    public function removeProductCart(Cart $cart, int $id): Cart
    {
        $productCart = $this->productCartRepository->findOneBy(['product' => $id, 'cart' => $cart]);

        $cart->removeProductCart($productCart);

        $this->entityManager->remove($productCart);
        $this->entityManager->flush();

        return $cart;
    }

    public function createCart(?string $sessionId, ?UserInterface $user): Cart
    {
        $cart = new Cart();

        $cart
            ->setSession($sessionId)
            ->setUser($user)
        ;

        $this->entityManager->persist($cart);
        $this->entityManager->flush();

        return $cart;
    }
}
