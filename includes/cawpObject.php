<?php

include_once CAWP_DIRECTORY . '/includes/cawpGenericItem.php';

/*
* This class represents a Collective Access Object.  It does not include
* all object attributes stored in the database, only the ones
* necessary.
*/

class cawpObject extends cawpGenericItem {


    function __construct($id, $source, $type, $idno, $access, $title) {
        parent::__construct($id, $source, $type, $idno, $access, $title);

        $this->getImagesFromDatabase();
    }


    function getAltTitles(){
        return parent::getAltTitles('ca_object_labels', 'object_id');
    }

    /**
     * Returns the name corresponding to the object's type.
     *
     * @return Name of the Object type, or null if not found.
     */
    function getType() {
        return parent::getType('object_types');
    }


    /**
     * Returns the name corresponding to the object's source_type.
     *
     * @return Source_Type name, or null if not found.
     */
    function getSource() {
        return parent::getSource('object_sources');
    }


    function getImagesFromDatabase() {
        parent::getImagesFromDatabase('ca_objects_x_object_representations', 'object_id');
    }

}