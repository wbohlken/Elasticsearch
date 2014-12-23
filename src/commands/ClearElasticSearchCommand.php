<?php
namespace Nutshell\Elasticsearch\Commands;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ClearElasticSearchCommand extends ElasticSearchCommand
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'clearElastic';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        parent::fire();
        $this->delete();
    }

    private function delete()
    {
        $this->collection->deleteFromIndex();
    }

}
