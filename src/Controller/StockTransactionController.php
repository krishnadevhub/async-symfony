<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;
use App\Message\PurchaseConfirmationNotification;

class StockTransactionController extends AbstractController
{
    #[Route('/buy', name: 'buy_stock')]
    public function buy(MessageBusInterface $messageBus): Response
    {
        // $notification->getOrder()->getBuyer()->getEmail() 

        // Dispatch confirmation notification message to the message bus
        $messageBus->dispatch(new PurchaseConfirmationNotification(
            new class {
                public function getId() {
                    return 1; // Example order ID
                }
                public function getBuyer() {
                    return new class {
                        public function getEmail() {
                            return 'email@example.com';
                        }
                    };
                }
            }
        ));

        // Display a confirmation message to the user
        return $this->render('stocks/example.html.twig');
    }
}

