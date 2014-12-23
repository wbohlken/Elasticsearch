<?php namespace Nutshell\Elasticsearch;

use Illuminate\Support\ServiceProvider;

class ElasticsearchServiceProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->package('nutshell/elasticsearch');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app['command.import'] = $this->app->share(function ($app) {
            return new Commands\ImportElasticSearchCommand();
        });
        $this->commands('command.import');
        $this->app['command.clear'] = $this->app->share(function ($app) {
            return new Commands\ClearElasticSearchCommand();
        });
        $this->commands('command.clear');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array();
    }

}
