<?php

namespace Nutshell\Elasticsearch;

use Elasticquent\ElasticquentTrait;
use Elasticquent\ElasticquentResultCollection;

trait ElasticSearchTrait {

    use ElasticquentTrait;

    /**
     * Search By Query and Filter
     * Overrides the searchByQuery method to add the filter setting
     *
     * Search with a query array
     *
     * @param   array $query
     * @param   array $aggregations
     * @param   array $sourceFields
     * @param   int $limit
     * @param   int $offset
     * @return  ResultCollection
     */
    public static function searchByQueryAndFilter($query = null, $filters = null, $sort = null, $limit = null, $offset = null)
    {
        $instance = new static;

        $params = $instance->getBasicEsParams(true, true, true, $limit, $offset);

        if ($query) {
            $params['body']['query'] = $query;
        }

        if ($filters) {
            $params['body']['filter'] = $filters;
        }

        if ($sort) {
            if(!is_array($sort)) {
                $sort = array($sort, 'asc');
            }
            $variable = $sort[0];
            $order = $sort[1];
            $params['body']['sort'][][$variable] = array('order' => $order);
        }
        $result = $instance->getElasticSearchClient()->search($params);
        $result['aggregations'] = array();

        return new ElasticquentResultCollection($result, $instance = new static);
    }

    /**
     * @param array $options
     * @return mixed
     * @throws \Elasticquent\Exception
     */
    public function finishSave(array $options) {
        try {
            if (isset(self::$elasticSearchRelations) && is_array(self::$elasticSearchRelations)) {
                $elasticSearchRelations = self::$elasticSearchRelations;
                $this->load($elasticSearchRelations);
            }
            $this->addToIndex();
        }
        catch(\Exception $e) {
//            return $e->getMessage();
        }
        return parent::finishSave($options);
    }

    /**
     * @return mixed
     */
    public function delete() {
        try {
            $this->removeFromIndex();
        }
        catch(\Exception $e) {
//            return $e->getMessage();
        }
        return parent::delete();
    }
} 