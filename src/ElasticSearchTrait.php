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
            $params['body']['sort'][][$sort['var']] = array('order' => $sort['order']);
        }
        $result = $instance->getElasticSearchClient()->search($params);

        return new ElasticquentResultCollection($result, $instance = new static);
    }
} 