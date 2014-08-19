<?php


class cawpGenericItem {

    protected $id;
    protected $source_id;
    protected $type_id;
    protected $idno;
    protected $access;
    protected $title;


    function __construct($id, $source, $type, $idno, $access, $title) {
        $this->id = $id;
        $this->source_id = $source;
        $this->type_id = $type;
        $this->idno = $idno;
        $this->access = $access;
        $this->title = $title;
    }

    /**
     * Returns the name corresponding to the object's type.
     *
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

} 