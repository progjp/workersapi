<?php

declare(strict_types=1);

use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return static function (RoutingConfigurator $routingConfigurator): void {
    $routingConfigurator->import([
        'path'      => '../src/Api/',
        'namespace' => 'App\Api',
    ], 'attribute');

    $routingConfigurator->add('app.swagger_ui', '/api/doc')
        ->methods(['GET'])
        ->defaults([
            '_controller' => 'nelmio_api_doc.controller.swagger_ui',
        ]);
};
