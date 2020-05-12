<?php

namespace App\Controller;

use App\Entity\Order;
use App\Form\OrderType;
use App\Service\OrderService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    /**
     * @Route("/order", name="place_order")
     */
    public function placeOrder(
        Request $request,
        OrderService $orderService
    ): Response {
        $orderModel = new Order();
        $form = $this->createForm(OrderType::class, $orderModel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $orderService->placeOrder($orderModel);

            return $this->redirectToRoute('product_index', ['page' => 1]);
        }

        return $this->render('order/create.html.twig', [
            'orderForm' => $form->createView(),
        ]);
    }
}
