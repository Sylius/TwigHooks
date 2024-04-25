<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator->services();

//    $services->set('twig_hooks.live_component.hydration.data_bag', DataBagHydrationExtension::class)
//        ->tag('live_component.hydration_extension')
//    ;
};
