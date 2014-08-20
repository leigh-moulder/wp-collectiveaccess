<?php

include_once CAWP_DIRECTORY . '/includes/cawpDBConn.php';
include_once CAWP_DIRECTORY . '/includes/cawpDBUtils.php';
include_once CAWP_DIRECTORY . '/includes/cawpImage.php';

class cawpGenericItem {

    protected $id;
    protected $source_id;
    protected $type_id;
    protected $idno;
    protected $access;
    protected $title;

    protected $primaryImage = null;
    protected $alternateImages = array();


    function __construct($id, $source, $type, $idno, $access, $title) {
        $this->id = $id;
        $this->source_id = $source;
        $this->type_id = $type;
        $this->idno = $idno;
        $this->access = $access;
        $this->title = $title;
    }

    /**
     * Returns a list of alternate titles for an object
     * @return array
     */
    function getAltTitles($table, $column){
        $db = cawpDBConn::getInstance()->getDB();
        $results = $db->get_results(
            "SELECT name FROM " . $table .
            " WHERE " . $column . " = " . $this->id .
            " AND is_preferred = 0");

        $titles = array();
        foreach ($results as $result) {
            array_push($titles, $result->name);
        }

        return $titles;
    }


    /**
     * Returns the name corresponding to the object's type.
     *
     * @param $item_type
     * @return Name of the Object type, or null if not found.
     */
    function getType($item_type) {
        $db = cawpDBConn::getInstance()->getDB();
        $results = $db->get_row(
            "SELECT list_items.item_value " .
            "FROM ca_lists as lists, ca_list_items as list_items " .
            "WHERE lists.list_code='" . $item_type . "' " .
            "AND lists.list_id = list_items.list_id " .
            "AND list_items.item_id=" . $this->type_id);

        return (!is_null($results)) ? $results->item_value : null;
    }


    /**
     * Returns the name corresponding to the object's source_type.
     *
     * @param $item_sources
     * @return Source_Type name, or null if not found.
     */
    function getSource($item_sources) {
        $db = cawpDBConn::getInstance()->getDB();
        $results = $db->get_row(
            "SELECT list_items.item_value " .
            "FROM ca_lists as lists, ca_list_items as list_items " .
            "WHERE lists.list_code='" . $item_sources . "' " .
            "AND lists.list_id = list_items.list_id " .
            "AND list_items.item_id=" . $this->source_id);

        return (!is_null($results)) ? $results->item_value : null;
    }


    function getImagesFromDatabase($representation_table, $column) {
        $db = cawpDBConn::getInstance()->getDB();
        $results = $db->get_results(
            "SELECT caor.representation_id, caoor.is_primary, caor.status, caor.media, caor.media_metadata, caor.type_id, caor.idno, caor.mimetype, caor.original_filename, caoor.rank " .
            "FROM ca_object_representations caor " .
            "INNER JOIN " . $representation_table . " AS caoor ON caor.representation_id = caoor.representation_id " .
            "WHERE caoor." . $column . " = " . $this->id . " " .
            "AND deleted = 0 " .
            "AND access = 1 " .
            "ORDER BY caoor.rank");

        $altImages = array();
        foreach ($results as $result) {
            $media = cawpDBUtils::unSerializeForDatabase($result->media);
            $image = new cawpImage($result->representation_id, $result->original_filename, $media);

            if ($result->is_primary) {
                $this->primaryImage = $image;
            }
            else {
                array_push($altImages, $image);
            }
        }

        $this->alternateImages = $altImages;
    }


    function getPrimaryImageURL($size = "original") {
        return $this->primaryImage->getURL($size);
    }

} 