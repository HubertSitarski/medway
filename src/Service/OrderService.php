<?php

namespace App\Service;

use App\Entity\Order;
use App\Entity\ProductOrder;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class OrderService
{
    private $cartService;
    private $entityManager;
    private $tokenStorage;

    public function __construct(
        CartService $cartService,
        EntityManagerInterface $entityManager,
        TokenStorageInterface $tokenStorage
    ) {
        $this->cartService = $cartService;
        $this->entityManager = $entityManager;
        $this->tokenStorage = $tokenStorage;
    }

    public function placeOrder(Order $order)
    {
        $cart = $this->cartService->findCart();
        $user = $this->tokenStorage->getToken()->getUser();

        $order
            ->setUser($user instanceof User ? $user : null)
            ->setPrice($cart->getFullPrice())
        ;

        foreach ($cart->getProductCarts() as $productCart) {
            $productOrder = new ProductOrder();

            $productOrder
                ->setPrice($productCart->getPrice())
                ->setQuantity($productCart->getQuantity())
                ->setProduct($productCart->getProduct())
            ;

            $productOrder->getProduct()->setQuantity($productOrder->getProduct()->getQuantity() - 1);

            $this->entityManager->persist($productOrder);
            $this->entityManager->persist($productOrder->getProduct());

            $order->addProductOrder($productOrder);
        }

        $this->entityManager->persist($order);
        $this->cartService->removeCart($cart);
        $this->entityManager->flush();
    }
}
