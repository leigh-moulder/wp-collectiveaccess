<?php


if (!class_exists('cawpDBUtils')) {

    class cawpDBUtils {

        public static function caSerializeForDatabase($data, $compress=false) {
            if ($compress && function_exists('gzcompress')) {
                return gzcompress(serialize($data));
            } else {
                return base64_encode(serialize($data));
            }
        }


        public static function unSerializeForDatabase($data) {
            if (is_array($data)) {
                return $data;
            }

            if (function_exists('gzuncompress') && ($ps_uncompressed_data = @gzuncompress($data))) {
                return unserialize($ps_uncompressed_data);
            }

            return unserialize(base64_decode($data));
        }
    }
} 