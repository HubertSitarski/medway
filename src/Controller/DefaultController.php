<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;

class DefaultController extends AbstractController
{
    public function index()
    {
        return new RedirectResponse($this->generateUrl('product_index', ['page' => 1]));
    }
}
