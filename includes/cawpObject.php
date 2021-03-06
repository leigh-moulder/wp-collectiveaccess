<?php

include_once CAWP_DIRECTORY . '/includes/cawpGenericItem.php';
include_once CAWP_DIRECTORY . '/includes/cawpConstants.php';


/**
 * Class cawpObject
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


    function getDescription() {
        if (isset($this->metadata)) {
            return $this->metadata['work_description'];
        }
        else {
            return "";
        }
    }

    /**
     * Queries the database for the object's metadata and then sets it.
     */
    function getMetadataFromDatabase() {
        $db = cawpDBConn::getInstance()->getDB();
        $query = "SELECT elements.element_code, a_values.value_longtext1 " .
            "FROM ca_attribute_values a_values, ca_attributes attributes, ca_metadata_elements elements " .
            "WHERE a_values.attribute_id=attributes.attribute_id " .
            "AND a_values.element_id = elements.element_id " .
            "AND attributes.table_num = " . cawpConstants::$CA_TABLES['ca_objects'] . " " .
            "AND attributes.row_id = " . $this->id . " " .
            "AND attributes.element_id IN " .
            "(SELECT element_id FROM ca_metadata_elements " .
            "WHERE element_code='work_dimensions' OR element_code='work_medium' " .
            "OR element_code='colorType' OR element_code='work_description' " .
            "OR element_code='date')";

        $results = $db->get_results($query);
        $metadata = array();
        foreach ($results as $result) {
            $metadata[$result->element_code] = $result->value_longtext1;
        }

        $this->metadata = $metadata;
    }


    function jsonSerialize() {
        $result = parent::jsonSerialize();

        $result['description'] = $this->getDescription();
        $result['type'] = "object";

        return $result;
    }
}