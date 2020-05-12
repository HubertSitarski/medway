<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Contracts\Translation\TranslatorInterface;

class LocaleSubscriber implements EventSubscriberInterface
{
    private $defaultLocale;
    private $translator;

    public function __construct($defaultLocale = 'pl', TranslatorInterface $translator)
    {
        $this->defaultLocale = $defaultLocale;
        $this->translator = $translator;
    }

    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest();

//        if (!$request->hasPreviousSession()) {
//            return;
//        }

        if (($locale = $request->query->get('_locale'))) {
            $request->getSession()->set('_locale', $locale);
            $request->setLocale($request->getSession()->get('_locale', $this->defaultLocale));
        } else {
            $request->setLocale($request->getSession()->get('_locale', $this->defaultLocale));
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => [['onKernelRequest', 20]],
        ];
    }
}