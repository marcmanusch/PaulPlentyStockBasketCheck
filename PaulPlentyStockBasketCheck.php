<?php
/**
 * Created by PhpStorm.
 * User: marc
 * Date: 19.04.18
 * Time: 17:26
 */

namespace PaulPlentyStockBasketCheck;

use Shopware\Components\Plugin;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class PaulPlentyStockBasketCheck extends Plugin
{
    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        $container->setParameter('paul_plenty_stock_basket_check.plugin_dir', $this->getPath());
        parent::build($container);
    }


}