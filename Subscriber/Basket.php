<?php

namespace PaulPlentyStockBasketCheck\Subscriber;

use Enlight\Event\SubscriberInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use PaulPlentyStockBasketCheck\ApiClient\Client;


class Basket implements SubscriberInterface
{

    /** @var  ContainerInterface */
    private $container;

    /**
     * Frontend contructor.
     * @param ContainerInterface $container
     **/
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            'sBasket::sGetBasket::after' => 'onAfterGetBasket',
        ];
    }

    /**
     * @param \Enlight_Event_EventArgs $args
     */
    public function onAfterGetBasket(\Enlight_Hook_HookArgs $args)
    {

        $config = $this->container->get('shopware.plugin.config_reader')->getByPluginName('PaulPlentyStockBasketCheck');

        // get plugin settings
        $paulActive = $config['active'];
        $paulServer = $config['server'];
        $paulUser = $config['user'];
        $paulPass = $config['pass'];

        if ($paulActive) {

            // Get Context
            $return = $args->getReturn();

            $itemsInBasket = [];

            foreach ($return['content'] as $key => $lineItem) {

                $itemsInBasket = [
                    'ordernumber' => $lineItem['ordernumber'],
                    'quantity' => $lineItem['quantity'],
                ];
            }

            $params = array(
                'numberExact' => $itemsInBasket[0]['ordernumber']
            );


            $client = new Client($paulUser, $paulPass, $paulServer);

            $response = $client->call('GET', '/rest/items/variations/', $params);


            $session = $this->container->get('session');
            $session->paulCheckStock = $response ;
        }
    }
}