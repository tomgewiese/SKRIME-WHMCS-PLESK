<?php
if (!defined("WHMCS"))
    die("This file cannot be accessed directly");

add_hook('ClientAreaPrimarySidebar', 1, function ($primarySidebar)
{
    $service = Menu::context('service');
    if (! $service)
        return;

    if (! isset($service->product))
        return;

    $product = $service->product;
    if (! $product)
        return;

    $serverType = $product->servertype;
    if ($serverType != 'skrimeplesklicense')
        return;

    if (isset($service->domainstatus) && $service->domainstatus != 'Active')
        return;

    $serviceId = (int) $service->id;

    $baseUrl = \WHMCS\Utility\Environment\WebHelper::getBaseUrl();
    if (! empty($baseUrl) && $baseUrl != '') {
        if ($baseUrl[0] != '/'){
            $baseUrl = '/' . $baseUrl;
        }
        if (substr($baseUrl, -1) == '/') {
            $baseUrl =  rtrim($baseUrl, "/");
        }
    }

    /** @var \WHMCS\View\Menu\Item $primarySidebar */
    if (! is_null($primarySidebar->getChild('Service Details Actions'))) {

        $primarySidebar->getChild('Service Details Actions')
            ->addChild('IP-Binding')
            ->setLabel('IP-Binding')
            ->setUri('clientarea.php?action=productdetails&amp;id='.$serviceId.'&amp;license_action=binding')
            ->setOrder(50);
    }
});