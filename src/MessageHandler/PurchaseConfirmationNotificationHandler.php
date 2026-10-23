<?php

namespace App\MessageHandler;

use App\Message\PurchaseConfirmationNotification;
use Mpdf\Mpdf;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Mime\Email;

#[AsMessageHandler]
class PurchaseConfirmationNotificationHandler
{
    public function __construct(private MailerInterface $mailer)
    {
        // You can inject services here if needed, e.g., a mailer service
    }
    public function __invoke(PurchaseConfirmationNotification $notification): void
    {        

        // Here you can implement the logic to send the purchase confirmation notification

        // Create a PDF contract note
        $mpdf = new Mpdf();
        $content = "<h1>Contract note for order #{$notification->getOrder()->getId()}</h1>";
        $content .= "<p>Total: $<b>18933</b></p>";
        
        $mpdf->WriteHTML($content);
        $contractNotePdf = $mpdf->Output('', 'S'); // Output as string

        // Email the contract note to the buyer
        $email = (new Email())
            ->from('sales@stocksapp.com')
            ->to($notification->getOrder()->getBuyer()->getEmail())
            ->subject('Contract note for order #' . $notification->getOrder()->getId())
            ->text('Here is your contract note')
            ->attach($contractNotePdf, 'contract_note.pdf', 'application/pdf');

        $this->mailer->send($email);

        // Example: Log the order confirmation (you can replace this with actual notification logic)
        // $this->logger->info('Purchase confirmation sent for order: ' . $order->getId());
    }

}