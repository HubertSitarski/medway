<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

class SessionService
{
    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    public function getCartSessionId(): string
    {
        if (!$this->session->get('cart_session_id')) {
            $this->session->set('cart_session_id', md5(uniqid(rand(), true)));
        }

        return $this->session->get('cart_session_id');
    }

    public function getCart()
    {
        $this->session->get('cart');
    }
}
