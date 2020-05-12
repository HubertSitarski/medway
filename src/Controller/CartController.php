<?php

namespace App\Controller;

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
        $cart = $this->cartService->findCart();
        $this->cartService->addProductCart($cart, $id);

        $this->addFlash('success', 'Dodano produkt');
        return $this->redirectToRoute('cart_show');
    }

    /**
     * @Route("/product/{id}/delete", name="delete_from_cart")
     */
    public function deleteFromCart(string $id): RedirectResponse
    {
        $cart = $this->cartService->findCart();
        $this->cartService->removeProductCart($cart, $id);

        $this->addFlash('success', 'Usunięto produkt');
        return $this->redirectToRoute('cart_show');
    }

    /**
     * @Route("/show", name="cart_show", methods={"GET"})
     */
    public function show(): Response
    {
        $cart = $this->cartService->findCart();

        return $this->render('cart/show.html.twig', [
            'cart' => $cart,
        ]);
    }
}
