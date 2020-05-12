<?php

namespace App\Service;

use App\Entity\Order;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

class MailService
{
    private $templating;
    private $mailer;
    private $translator;

    public function __construct(Environment $templating, \Swift_Mailer $mailer, TranslatorInterface $translator)
    {
        $this->translator = $translator;
        $this->templating = $templating;
        $this->mailer = $mailer;
    }

    public function sendOrderMail(Order $order)
    {
        $message = (new \Swift_Message($this->translator->trans('order_placed')))
            ->setFrom('test@test.com')
            ->setTo($order->getEmail())
            ->setBody(
                $this->templating->render(
                    'emails/order.html.twig'
                ),
                'text/html'
            )
            ->attach(new \Swift_Attachment($this->generatePdf($order), sprintf('order.pdf', date('Y-m-d'))));
        ;

        $this->mailer->send($message);
    }

    private function generatePdf(Order $order)
    {
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        $dompdf = new Dompdf($pdfOptions);

        $html = $this->templating->render('emails/pdf.html.twig', [
            'order' => $order,
        ]);

        $dompdf->loadHtml($html);

        $dompdf->setPaper('A4', 'portrait');

        $dompdf->render();

        return $dompdf->output();
    }
}
