<?php

include_once CAWP_DIRECTORY . '/includes/cawpGenericItem.php';

/**
 * This class represents a Collective Access Collection.  It does not include
 * all collection attributes stored in the database, only the ones
 * necessary.
 */
class cawpCollection extends cawpGenericItem {

    function __construct($id, $source, $type, $idno, $access, $title) {
        parent::__construct($id, $source, $type, $idno, $access, $title);

        $this->getImagesFromDatabase();
    }


    function getAltTitles() {
        return parent::getAltTitles('ca_collection_labels', 'collection_id');
    }


    function getType() {
        return parent::getType('collection_types');
    }


    function getImagesFromDatabase() {
        parent::getImagesFromDatabase('ca_object_representations_x_collections', 'collection_id');
    }


    function jsonSerialize() {
        $result = parent::jsonSerialize();
        $result['type'] = "collection";

        return $result;
    }

}