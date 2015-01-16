<?php
namespace Nutshell\Elasticsearch\Commands;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ImportElasticSearchCommand extends ElasticSearchCommand
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'importElastic';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        parent::fire();
        $this->import();
    }

    /**
     * Autoload all relations specified by the $elasticSearchRelations variable.
     */
    protected function prepare()
    {
        $modelName = $this->model;
        if (isset($modelName::$elasticSearchRelations) && is_array($modelName::$elasticSearchRelations)) {
            $elasticSearchRelations = $modelName::$elasticSearchRelations;
            $this->collection->load($elasticSearchRelations);
        }
        parent::prepare();
    }

    /**
     *
     */
    private function import()
    {
        $this->collection->addToIndex();
    }

}
