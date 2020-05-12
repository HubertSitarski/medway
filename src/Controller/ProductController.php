<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/products")
 */
class ProductController extends AbstractController
{
    private $paginator;

    public function __construct(PaginatorInterface $paginator)
    {
        $this->paginator = $paginator;
    }

    /**
     * @Route("/{page}", name="product_index", methods={"GET"})
     */
    public function index(
        ProductRepository $productRepository,
        string $page
    ): Response {
        $products = $this->paginator->paginate($productRepository->findAvailable(), $page, 4);

        return $this->render('product/index.html.twig', [
            'products' => $products,
        ]);
    }
}
