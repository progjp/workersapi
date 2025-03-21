<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->extension('doctrine_migrations', [
        'migrations_paths' => [
            'App\Infrastructure\Db\Doctrine\Migration'
                => '%kernel.project_dir%/src/Infrastructure/Db/Doctrine/Migration',
        ],
        'enable_profiler'  => false,
        'all_or_nothing'   => true,
    ]);
};
