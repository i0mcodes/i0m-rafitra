<?php

namespace Iom\Rafitra;

use Illuminate\Routing\Router;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RafitraServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/rafitra.php' => config_path('rafitra.php')
        ], 'config');
        
        $this->loadConfig();
        parent::boot();
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerAssets();
    }

    public function registerAssets()
    {
        /**
         * envoyer le fichier de configuration pour changer les configurations par celle de l'application
         */
        $this->publishes([
            __DIR__ . 'config/rafitra.php',config_path('rafitra.php')
        ]);
    }

    public function loadConfig()
    {
        /* chargement de fichier config de asgard dans le repertoire config du site **/
        $config = $this->app['config'];

        if($config->get('rafitra') === null){
            $config->set('rafitra', require __DIR__ . '/config/rafitra.php');
        }

    }

    public function map(Router $router)
    {
        /**
         * charger la configuration du package rafitra
         */
        $config = $this->app['config']['rafitra'];

        $middleware = $config['protection_middleware'];

        /* recuperer les namespaces declarées dans le fichier config */
        $highLevelParts = array_map(function ($namespace) {
            return glob(sprintf('%s%s*', $namespace, DIRECTORY_SEPARATOR), GLOB_ONLYDIR);
        }, $config['namespaces']);

        foreach ($highLevelParts as $parts => $partComponent)
        {
            /* parcourir tous les modules dans les namespaces donnée dans le fichier config */
            foreach ($partComponent as $componentRoot)
            {
                $component = substr($componentRoot, strrpos($componentRoot, DIRECTORY_SEPARATOR) + 1);

                /* recuperer le namespace des modules existants */
                $namespace = sprintf(
                    '%s\\%s\\Controllers',
                    $parts,
                    $component
                );

                $fileNames = [
                    'routes' => false,
                    'routes_protected' => true,
                    'routes_public' => false,
                ];

                /* recuperer toutes les fichiers routes de chaque modules */
                foreach ($fileNames as $fileName => $protected) {
                    $path = sprintf('%s/%s.php', $componentRoot, $fileName);
                    if (!file_exists($path)) {
                        continue;
                    }

                    if (!file_exists($path)) {
                        continue;
                    }

                    $router->group([
                        'middleware' => $protected ? $middleware : [],
                        'namespace'  => $namespace,
                    ], function ($router) use ($path) {
                        require $path;
                    });
                }

            }
        }
    }

}
