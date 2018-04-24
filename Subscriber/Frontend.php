<?php

namespace PaulPlentyStockBasketCheck\Subscriber;

use Enlight\Event\SubscriberInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use PaulPlentyStockBasketCheck\ApiClient\Client;


class Frontend implements SubscriberInterface
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
            'Enlight_Controller_Action_PostDispatchSecure_Frontend' => 'onAddBasket',
        ];
    }

    /**
     * @param \Enlight_Event_EventArgs $args
     */
    public function onAddBasket(\Enlight_Event_EventArgs $args)
    {
        /** @var $controller \Enlight_Controller_Action */
        $controller = $args->getSubject();
        $view = $controller->View();
        $view->addTemplateDir($this->container->getParameter('paul_plenty_stock_basket_check.plugin_dir') . '/Resources/Views');
        $config = $this->container->get('shopware.plugin.config_reader')->getByPluginName('PaulPlentyStockBasketCheck');

        // get plugin settings
        $paulActive = $config['active'];
        $paulServer = $config['server'];
        $paulUser = $config['user'];
        $paulPass = $config['pass'];

        $mpn = 'PGVMSTAND';

        $params = array(
            'numberExact' => $mpn
        );

        if($paulActive) {

            $client = new Client($paulUser, $paulPass, $paulServer);

            $response = $client->call('GET', '/rest/items/variations/', $params);
            echo var_dump($response);

        }
    }
}