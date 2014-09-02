<?php

include_once CAWP_DIRECTORY . '/includes/cawpDBConn.php';
require_once CAWP_DIRECTORY . '/includes/cawpCollection.php';


class cawpCollectionService {


    public static function get_collections($public_only = true) {
        if (!cawpDBConn::getInstance()->is_db_connected()) {
            return array();
        }

        $db = cawpDBConn::getInstance()->getDB();

        $query = "SELECT collections.collection_id, collections.source_id, collections.type_id, collections.idno, collections.access, labels.name " .
            "FROM ca_collections as collections, ca_collection_labels as labels " .
            "WHERE collections.collection_id = labels.collection_id " .
            "AND collections.deleted=0 " .
            "AND labels.is_preferred=1 ";

        if ($public_only) {
            $query = $query . "AND collections.access=1";
        }

        $results = $db->get_results($query);
        $collections = array();

        foreach ($results as $result) {
            $collection = new cawpCollection($result->collection_id, $result->source_id, $result->type_id, $result->idno, $result->access, $result->name);
            array_push($collections, $collection);
        }

        return $collections;
    }

} 