<?php

if (!class_exists('caqpDBConn')) {
    class cawpDBConn {
        var $server;
        var $database;
        var $user;
        var $password;

        private $db;

        function cawpDBConn($server, $database, $user, $password) {
            $this->server = $server;
            $this->database = $database;
            $this->user = $user;
            $this->password = $password;

            try {
                $this->db = new wpdb($user, $password, $database, $server);
                $this->db->show_errors();
            }
            catch (Exception $e) {
                echo $e->getMessage();
            }

        }

        function is_db_connected() {
            return $this->db->ready;
        }


        function test_connection() {
            $result = $this->db->get_var("SELECT COUNT(*) FROM ca_users");
            if (isset($result)) {
            }
            else {
            }
        }
    }

}