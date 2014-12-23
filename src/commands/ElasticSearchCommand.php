<?php
namespace Nutshell\Elasticsearch\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ElasticSearchCommand extends Command
{
    const DEFAULT_NUMBER_OF_RECORDS = 500;

    protected $validClasses = array('Nutshell\Elasticsearch\ElasticSearchTrait', 'Elasticquent\ElasticquentTrait');

    protected $model;
    protected $all;
    protected $limit;
    protected $offset;

    protected $collection;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    protected function fire()
    {
        $this->model = $this->argument('model');
        $this->all = $this->option('a');
        $this->limit = $this->option('limit');
        if (!$this->limit) {
            $this->limit = self::DEFAULT_NUMBER_OF_RECORDS;
        }
        $this->offset = $this->option('offset');
        if (!$this->offset) {
            $this->offset = 0;
        }
        $this->validate();
        $this->prepare();
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array(
            array('model', InputArgument::REQUIRED, 'Name of the model to import.'),
        );
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return array(
            array('a', null, InputOption::VALUE_NONE, 'Import all records.', null),
            array('limit', null, InputOption::VALUE_OPTIONAL, 'Number of records to import (default ' . self::DEFAULT_NUMBER_OF_RECORDS . ').'),
            array('offset', null, InputOption::VALUE_OPTIONAL, 'Number of records to skip (default 0).'),
        );
    }

    /**
     * @return bool
     */
    protected function isValidModel()
    {
        $inheritanceTree = array_merge(array($this->model), class_parents($this->model));
        foreach($inheritanceTree as $class)
        foreach (class_uses($class) as $usedClass) {
            if (in_array($usedClass, $this->validClasses)) {
                return true;
            }
        }
        return false;
    }

    /**
     *
     */
    protected function validate()
    {
        if (class_exists($this->model)) {
            if (!$this->isValidModel()) {
                die("\"" . $this->model . "\" does not support the Elasticsearch libary.\n"
                    . "Please use one of the following:\n"
                    . implode("\n", $this->validClasses) . "\n");
            }
        } else {
            die("Model " . $this->model . " not found.\n");
        }
    }

    /**
     *
     */
    protected function prepare()
    {
        $builder = new $this->model();
        if ($this->all) {
            $this->collection = $builder->all();
        } else {
            $this->collection = $builder->take($this->limit)->skip($this->offset)->get();
        }

        // FIXME: If the primary key needs to be different, set it to each record individually.
        if(isset($builder->elasticSearchPrimaryKey)) {
            foreach($this->collection as $record) {
                $record->primaryKey = $builder->elasticSearchPrimaryKey;
            }
        }
    }

} 