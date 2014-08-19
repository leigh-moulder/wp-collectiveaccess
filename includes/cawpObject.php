<?php

include_once CAWP_DIRECTORY . '/includes/cawpGenericItem.php';
include_once CAWP_DIRECTORY . '/includes/cawpDBConn.php';
include_once CAWP_DIRECTORY . '/includes/cawpDBUtils.php';
include_once CAWP_DIRECTORY . '/includes/cawpImage.php';

/*
* This class represents a Collective Access Object.  It does not include
* all object attributes stored in the database, only the ones
* necessary.
*/

class cawpObject extends cawpGenericItem {

    protected $primaryImage = null;
    protected $alternateImages = array();


    function cawpObject($id, $source, $type, $idno, $access, $title) {
        parent::__construct($id, $source, $type, $idno, $access, $title);

        $this->getImages();
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


    private function getImages() {

        $db = cawpDBConn::getInstance()->getDB();
        $results = $db->get_results(
            "SELECT caor.representation_id, caoor.is_primary, caor.status, caor.media, caor.media_metadata, caor.type_id, caor.idno, caor.mimetype, caor.original_filename, caoor.rank " .
            "FROM ca_object_representations caor " .
            "INNER JOIN ca_objects_x_object_representations AS caoor ON caor.representation_id = caoor.representation_id " .
            "WHERE caoor.object_id = " . $this->id . " " .
            "AND deleted = 0 " .
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