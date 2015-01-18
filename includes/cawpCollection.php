<?php

include_once CAWP_DIRECTORY . '/includes/cawpGenericItem.php';
include_once CAWP_DIRECTORY . '/includes/cawpObjectService.php';

/**
 * This class represents a Collective Access Collection.  It does not include
 * all collection attributes stored in the database, only the ones
 * necessary.
 */
class cawpCollection extends cawpGenericItem {

    protected $objects;

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


    function getDescription() {
        if (isset($this->metadata)) {
            return $this->metadata['description'];
        }
        else {
            return "";
        }
    }


    function getImagesFromDatabase() {
        parent::getImagesFromDatabase('ca_object_representations_x_collections', 'collection_id');
    }


    function getObjectsFromDatabase() {
        $this->objects = cawpObjectService::get_objects_by_collection($this->id);
    }

    function getMetadataFromDatabase() {
        $db = cawpDBConn::getInstance()->getDB();
        $query = "SELECT elements.element_code, a_values.value_longtext1 " .
                 "FROM ca_attribute_values a_values, ca_attributes attributes, ca_metadata_elements elements " .
                 "WHERE a_values.attribute_id=attributes.attribute_id " .
                 "AND a_values.element_id = elements.element_id " .
                 "AND attributes.table_num = " . cawpConstants::$CA_TABLES['ca_collections'] . " " .
                 "AND attributes.row_id = " . $this->id . " " .
                 "AND attributes.element_id IN " .
                 "  (SELECT element_id FROM ca_metadata_elements " .
                 "   WHERE element_code='description' " .
                 "   OR element_code='date')";

        $results = $db->get_results($query);
        $metadata = array();
        foreach ($results as $result) {
            $metadata[$result->element_code] = $result->value_longtext1;
        }

        $this->metadata = $metadata;
    }


    function jsonSerialize() {
        $result = parent::jsonSerialize();
        $result['type'] = "collection";

        $objCount = count($this->objects);
        for ($i = 0; $i < $objCount; $i++) {
            $result['objects']['object_' . $i] = $this->objects[$i]->jsonSerialize();
        }

        return $result;
    }

}