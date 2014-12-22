<?php
namespace Nutshell\Elasticsearch;

class ElasticSearchQueryBuilder {

    private $filterRange;
    private $filterTerms;
    private $queryMatch;
    private $queryMultiMatch;

    /**
     *
     */
    public function __construct() {
        $this->filterRange = array();
        $this->filterTerms = array();
        $this->queryMatch = array();
        $this->queryMultiMatch = array();
    }

    /**
     * Set a range filter by from and to range.
     *
     * @param $attributeName
     * @param array $range
     */
    public function addFilterRange($attributeName, $from, $to) {
        $newRange = array();
        $newRange['from'] = $from;
        $newRange['to'] = $to;
        $this->filterRange[]['range'][$attributeName] = $newRange;
    }

    /**
     * Set a filter on an array of values.
     * WHERE IN () SQL equivalent.
     *
     * @param $attributeName
     * @param array $values
     */
    public function addFilterArray($attributeName, array $values) {
        $this->filterTerms[]['terms'][$attributeName] = $values;
    }

    /**
     * Sets a value that must be exactly matched.
     *
     * @param $attributeName
     * @param $value
     */
    public function addQueryMatch($attributeName, $value) {
        $this->queryMatch[]['match'][$attributeName] = $value;
    }

    /**
     * Sets a value that must be matched in multiple fields
     *
     * @param $attributeName
     * @param $value
     */
    public function addQueryMultiMatch(array $fields, $value) {
        $newMultiMatch = array();
        $newMultiMatch['query'] = $value;
        $newMultiMatch['fields'] = $fields;
        $this->queryMatch[]['multi_match'] = $newMultiMatch;
    }

    /**
     * Returns the query.
     *
     * @return array
     */
    public function getQuery() {
        $query = array();

        $queryMatches = array_merge($this->queryMatch, $this->queryMultiMatch);
        $nestedQueryMust = array(array('query' => array('bool' => array('must' => $queryMatches))));
        $must = array_merge($this->filterTerms, $this->filterRange, $nestedQueryMust);

        $query['filtered']['filter']['bool']['must'] = $must;
        return $query;
    }

} 