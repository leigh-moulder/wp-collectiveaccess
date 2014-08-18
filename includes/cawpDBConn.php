<?php

if (!class_exists('cawpDBConn')) {
    class cawpDBConn {

        private static $instance = null;

        private $server;
        private $database;
        private $user;
        private $password;

        private $db;

        public static function getInstance() {

            if (!self::$instance) {
                self::$instance = new self();
            }

            return self::$instance;
        }


        private function __construct() {
            $cawp_config_manager = cawp_get_configuration();
            $this->server = $cawp_config_manager->get('ca_host');
            $this->database = $cawp_config_manager->get('ca_database');
            $this->user = $cawp_config_manager->get('ca_username');
            $this->password = $cawp_config_manager->get('ca_password');

            try {
                $this->db = new wpdb($this->user, $this->password, $this->database, $this->server);
                $this->db->show_errors();
            }
            catch (Exception $e) {
                echo $e->getMessage();
            }
        }

        /**
         * Recreates the database connection instance.
         * This should be run any time the configuration management settings
         * change.
         */
        public function refreshDBConn() {
            self::$instance = null;
            self::$instance = new self();
        }


        public function is_db_connected() {
            return $this->db->ready;
        }


        public function getDB() {
            return $this->db;
        }


        public function get_objects($public_only = true) {
            if (!$this->is_db_connected()) {
                return array();
            }

            require_once CAWP_DIRECTORY . '/includes/cawpObject.php';

            $query = "SELECT objects.object_id as id, objects.source_id as source_id, objects.type_id as type_id, objects.idno as idno, " .
                     "objects.access as access, labels.name as name " .
                     "FROM ca_objects as objects, ca_object_labels as labels " .
                     "WHERE objects.object_id = labels.object_id " .
                     "AND objects.deleted=0 " .
                     "AND labels.is_preferred=1 ";

            if ($public_only) {
                $query = $query . "AND objects.access=1";
            }

            $results = $this->db->get_results($query);
            $objects = array();
            foreach ($results as $result) {
                $object = new cawpObject($result->id, $result->source_id, $result->type_id, $result->idno, $result->access, $result->name);
                array_push($objects, $object);
            }

            return $objects;
        }


        public function get_collections($public_only = true) {
            if (!$this->is_db_connected()) {
                return array();
            }

            $collections = array();
            return $collections;
        }
    }

}