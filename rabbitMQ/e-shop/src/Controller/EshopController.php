<?php

namespace App\Controller;

use App\Message\Command\CreateOrder;
use App\Message\Command\SignUpSms;
use App\Message\Query\SearchQuery;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Bridge\Amqp\Transport\AmqpStamp;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class EshopController extends AbstractController
{
    use HandleTrait;

    private MessageBusInterface $messageBus;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    #[Route('/', name: 'eshop')]
    public function index(): Response
    {
        return $this->render('eshop/index.html.twig', [
            'controller_name' => 'EshopController',
        ]);
    }

    #[Route("/search", name: 'search')]
    public function search(): Response
    {
        $search = 'laptops';

        // $this->messageBus->dispatch(new SearchQuery($search));
        $result = $this->handle(new SearchQuery($search));

        return new Response('Your search results for '.$search.$result);
    }

    #[Route('/signup-sms', name: 'signup-sms')]
    public function SignUpSMS(): Response
    {
        $phoneNumber = '111 222 333 ';
        $attributes = [];
        $routingKey = ['sms1', 'sms2'];
        $routingKey = $routingKey[random_int(0, 1)];
        $this->messageBus->dispatch(new SignUpSms($phoneNumber), [new AmqpStamp($routingKey, AMQP_NOPARAM, $attributes)]);

        return new Response(sprintf('Your phone number %s successfully signed up to SMS newsletter!',$phoneNumber));
    }

    #[Route('/order', name: "order")]
    public function Order(): Response
    {
        $productId = 243;
        $productName = 'product name';
        $productAmount = 2;
        // save the order in the database

        $this->messageBus->dispatch(new CreateOrder($productId, $productAmount));

        return new Response('You successfully ordered your product!: '.$productName);
    }
}
