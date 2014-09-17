<?php

/**
 * Class cawpDBConn
 * Provides an singleton interface for the database connection.
 */
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
}