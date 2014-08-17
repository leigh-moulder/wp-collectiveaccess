<?php

include_once CAWP_DIRECTORY . '/includes/cawpDBConn.php';

/*
 * This class represents a Collective Access Object.  It does not include
 * all object attributes stored in the database, only the one's
 * necessary.
 */
if (!class_exists('cawpObject')) {

    class cawpObject {

        protected $id;
        protected $source_id;
        protected $type_id;
        protected $idno;
        protected $access;
        protected $title;

        function cawpObject($id, $source, $type, $idno, $access, $title) {
            $this->id = $id;
            $this->source_id = $source;
            $this->type_id = $type;
            $this->idno = $idno;
            $this->access = $access;
            $this->title = $title;
        }

        function getAltTitles(){
            $db = cawpDBConn::getInstance()->getDB();
            $results = $db->get_results(
                "SELECT name FROM ca_object_labels " .
                "WHERE object_id=" . $this->id .
                " AND is_preferred = 0");

            $titles = array();
            foreach ($results as $result) {
                array_push($titles, $result->name);
            }

            return $titles;
        }


        function getObjectType() {
            $db = cawpDBConn::getInstance()->getDB();
            $results = $db->get_row(
                "SELECT list_items.item_value " .
                "FROM ca_lists as lists, ca_list_items as list_items " .
                "WHERE lists.list_code='object_types' " .
                "AND lists.list_id = list_items.list_id " .
                "AND list_items.item_id=" . $this->type_id);

            return (notnull($results)) ? $results->item_value : null;
        }


        function getObjectSource() {
            $db = cawpDBConn::getInstance()->getDB();
            $results = $db->get_row(
                "SELECT list_items.item_value " .
                "FROM ca_lists as lists, ca_list_items as list_items " .
                "WHERE lists.list_code='object_sources' " .
                "AND lists.list_id = list_items.list_id " .
                "AND list_items.item_id=" . $this->source_id);

            return (notnull($results)) ? $results->item_value : null;
        }

        function getPrimaryImageURL() {
            $db = cawpDBConn::getInstance()->getDB();
            $results = $db->get_row(
                "SELECT caor.representation_id, caor.media, caor.status, l.name, caor.media, caor.media_metadata, caor.type_id, caor.idno, caor.mimetype, caor.original_filename, caoor.rank " .
                "FROM ca_object_representations caor " .
                "INNER JOIN ca_objects_x_object_representations AS caoor ON caor.representation_id = caoor.representation_id " .
                "LEFT JOIN ca_locales AS l ON caor.locale_id = l.locale_id " .
                "WHERE caoor.object_id = " . $this->id .
                "AND deleted = 0 " .
                "AND (caoor.is_primary = 1) " .
                "ORDER BY caoor.rank, caoor.is_primary DESC");
        }

    }
}