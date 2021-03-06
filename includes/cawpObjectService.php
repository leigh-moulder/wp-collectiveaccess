<?php

include_once CAWP_DIRECTORY . '/includes/cawpDBConn.php';
require_once CAWP_DIRECTORY . '/includes/cawpObject.php';

/**
 * Class cawpObjectService
 * Provides methods to query the database for Objects.
 */
class cawpObjectService{

    public static function get_objects($public_only = true, $shallow = true) {

        if (!cawpDBConn::getInstance()->is_db_connected()) {
            return array();
        }

        $db = cawpDBConn::getInstance()->getDB();

        $query = "SELECT objects.object_id, objects.source_id, objects.type_id, objects.idno, objects.access, labels.name " .
            "FROM ca_objects as objects, ca_object_labels as labels " .
            "WHERE objects.object_id = labels.object_id " .
            "AND objects.deleted=0 " .
            "AND labels.is_preferred=1 ";

        if ($public_only) {
            $query = $query . "AND objects.access=1";
        }

        $results = $db->get_results($query);
        $objects = array();
        foreach ($results as $result) {
            $object = new cawpObject($result->object_id, $result->source_id, $result->type_id, $result->idno, $result->access, $result->name);

            if (!$shallow) {
                $object->getMetadataFromDatabase();
            }
            array_push($objects, $object);
        }

        return $objects;
    }


    public static function get_object_by_id($id, $shallow = true) {

        if (!cawpDBConn::getInstance()->is_db_connected()) {
            return array();
        }

        if ($id == null) {
            return null;
        }

        $db = cawpDBConn::getInstance()->getDB();

        $query = "SELECT objects.object_id, objects.source_id, objects.type_id, objects.idno, objects.access, labels.name " .
            "FROM ca_objects as objects, ca_object_labels as labels " .
            "WHERE objects.object_id = labels.object_id " .
            "AND objects.deleted=0 " .
            "AND labels.is_preferred=1 " .
            "AND objects.object_id = " . $id;

        $result = $db->get_row($query);
        $object = new cawpObject($result->object_id, $result->source_id, $result->type_id, $result->idno, $result->access, $result->name);

        if (!$shallow) {
            $object->getMetadataFromDatabase();
        }

        return $object;
    }


    public static function get_objects_by_collection($collection_id, $shallow = true) {

        if (!cawpDBConn::getInstance()->is_db_connected()) {
            return array();
        }

        if ($collection_id == null) {
            return null;
        }

        $db = cawpDBConn::getInstance()->getDB();

        $query = "SELECT objects.object_id, objects.source_id, objects.type_id, objects.idno, objects.access, labels.name " .
                 "FROM ca_objects as objects, ca_object_labels as labels " .
                 "WHERE objects.object_id = labels.object_id " .
                 "AND objects.deleted=0 " .
                 "AND labels.is_preferred=1 " .
                 "AND objects.object_id in ( " .
                 " SELECT object_id FROM ca_objects_x_collections WHERE collection_id = " . $collection_id . " )";

        $results = $db->get_results($query);
        $objects = array();
        foreach ($results as $result) {
            $object = new cawpObject($result->object_id, $result->source_id, $result->type_id, $result->idno, $result->access, $result->name);

            if (!$shallow) {
                $object->getMetadataFromDatabase();
            }
            array_push($objects, $object);
        }

        return $objects;
    }
} 