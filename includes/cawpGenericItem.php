<?php

include_once CAWP_DIRECTORY . '/includes/cawpDBConn.php';
include_once CAWP_DIRECTORY . '/includes/cawpDBUtils.php';
include_once CAWP_DIRECTORY . '/includes/cawpImage.php';

class cawpGenericItem implements JsonSerializable {

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

    function getId() {
        return $this->id;
    }


    function getTitle() {
        return $this->title;
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
            "AND access = " . $this->access . " " .
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


    function getPrimaryImage() {
        return $this->primaryImage;
    }


    function getPrimaryImageURL($size = "original") {
        if ($this->primaryImage == null) {
            return  CAWP_PLUGIN_URL . '/images/default_object_preview.jpg';
        }
        else {
            return $this->primaryImage->getURL($size);
        }
    }


    function getAlternativeImages() {
        return $this->alternateImages;
    }


    /**
     * Returns a particular metadata value.
     *
     * @param $element_code : metadata key
     * @param $item_id : primary key of the object to return the metadata for
     * @param $table_num : corresponding object table id
     * @return mixed
     */
    function getMetaDataValue($element_code, $item_id, $table_num) {
        $db = cawpDBConn::getInstance()->getDB();
        $query = "SELECT attribute_values.value_longtext1 " .
            "FROM ca_metadata_elements as elements, ca_attributes as attributes, ca_attribute_values as attribute_values " .
            "WHERE elements.element_code='" . $element_code . "' " .
            "AND elements.element_id = attributes.element_id " .
            "AND attributes.attribute_id = attribute_values.attribute_id " .
            "AND attributes.row_id=" . $item_id . " " .
            "AND attributes.table_num=" . $table_num;

        $result = $db->get_row($query);

        return $result->value_longtext1;
    }


    /**
     * Returns the display label for a given metadata attribute.
     *
     * @param $element_code : metadata key to search for.
     * @return mixed
     */
    function getMetadataLabel($element_code) {
        $db = cawpDBConn::getInstance()->getDB();
        $query = "SELECT name_singular " .
                 "FROM ca_list_item_labels " .
                 "WHERE item_id = " . $element_code;

        $result = $db->get_row($query);
        return $result->name_singular;
    }

    /**
     * (PHP 5 &gt;= 5.4.0)<br/>
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     */
    function jsonSerialize(){
        $result = array();

        $result['id'] = $this->id;
        $result['title'] = $this->title;

        $result['img_primary_main'] = $this->getPrimaryImageURL(cawpConstants::IMAGE_MAIN);
        $result['img_primary_thumb'] = $this->getPrimaryImageURL(cawpConstants::IMAGE_ALT);

        $altImgCount = count($this->alternateImages);
        for ($i = 0; $i < $altImgCount; $i++) {
            $result['alt_img']['alt_img_' . $i . '_thumb'] = $this->alternateImages[$i]->getURL(cawpConstants::IMAGE_ALT);
        }

        return $result;
    }
}