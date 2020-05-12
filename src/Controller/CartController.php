<?php

namespace App\Controller;

use App\Entity\ProductCart;
use App\Repository\CartRepository;
use App\Repository\ProductRepository;
use App\Service\CartService;
use App\Service\SessionService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CartController
 * @package App\Controller
 *
 * @Route("cart")
 */
class CartController extends AbstractController
{
    private $productRepository;
    private $cartRepository;
    private $cartService;
    private $sessionService;

    public function __construct(
        ProductRepository $productRepository,
        CartRepository $cartRepository,
        SessionService $sessionService,
        CartService $cartService
    ) {
        $this->productRepository = $productRepository;
        $this->cartRepository = $cartRepository;
        $this->sessionService = $sessionService;
        $this->cartService = $cartService;
    }

    /**
     * @Route("/product/{id}/add", name="add_to_cart")
     */
    public function addToCart(string $id): RedirectResponse
    {
        if (!$this->getUser()) {
            $sessionId = $this->sessionService->getCartSessionId();
            $cart = $this->cartRepository->findOneBy(['session' => $sessionId]) ?? $this->cartService->createCart($sessionId);
            $this->cartService->addProductCart($cart, $id);
        }

        $this->addFlash('success', 'Dodano produkt');
        return $this->redirectToRoute('cart_show');
    }

    /**
     * @Route("/show", name="cart_show", methods={"GET"})
     */
    public function show(): Response
    {
        if (!$this->getUser()) {
            $sessionId = $this->sessionService->getCartSessionId();
            $cart = $this
                    ->cartRepository
                    ->findOneBy(['session' => $sessionId]) ?? $this->cartService->createCart($sessionId)
            ;
        }

        return $this->render('cart/show.html.twig', [
            'cart' => $cart,
        ]);
    }
}
