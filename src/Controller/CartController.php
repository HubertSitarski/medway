<?php

namespace App\Controller;

use App\Repository\CartRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
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
    private $session;

    public function __construct(ProductRepository $productRepository, SessionInterface $session, CartRepository $cartRepository)
    {
        $this->productRepository = $productRepository;
        $this->cartRepository = $cartRepository;
        $this->session = $session;
    }

    /**
     * @Route("/product/{id}/add", name="add_to_cart")
     */
    public function addToCart(string $id): RedirectResponse
    {
        if (!$this->getUser()) {
            if (!$this->session->get('cart_session_id')) {
                $this->session->set('cart_session_id', md5(uniqid(rand(), true)));
            }

            if (($cart = $this->cartRepository->findOneBy(['session_id' => $this->session->get('cart_session_id')]))) {
            }

            $sessionId = $this->session->get('cart_session_id');
        }

        $this->addFlash('success', 'Dodano produkt');
        return $this->redirectToRoute('cart_show');
    }

    /**
     * @Route("/show", name="cart_show", methods={"GET"})
     */
    public function show(): Response
    {
        return $this->render('cart/show.html.twig', [
            'cart' => $this->session->get('cart'),
        ]);
    }
}
