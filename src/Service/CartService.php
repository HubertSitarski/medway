<?php

namespace App\Service;

use App\Entity\Cart;
use App\Entity\ProductCart;
use App\Repository\CartRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;

class CartService
{
    private $cartRepository;
    private $productRepository;
    private $entityManager;

    public function __construct(
        CartRepository $cartRepository,
        ProductRepository $productRepository,
        EntityManagerInterface $entityManager
    ) {
        $this->cartRepository = $cartRepository;
        $this->productRepository = $productRepository;
        $this->entityManager = $entityManager;
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

    public function createCart(string $sessionId): Cart
    {
        $cart = new Cart();

        $cart
            ->setSession($sessionId)
        ;

        $this->entityManager->persist($cart);
        $this->entityManager->flush();

        return $cart;
    }
}
