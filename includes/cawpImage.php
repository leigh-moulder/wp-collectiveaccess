<?php

class cawpImage {
    private $id;
    private $original_name;
    private $media;

    function cawpImage($id, $name, $media) {
        $this->id = $id;
        $this->original_name = $name;
        $this->media = $media;
    }


    function getId() {
        return $this->id;
    }

    function getOriginalName() {
        return $this->original_name;
    }


    function getHeight($size = "original") {
        if (is_array($this->media[$size])) {
            return $this->media[$size]['HEIGHT'];
        }
        else {
            return null;
        }
    }


    function getWidth($size = "original") {
        if (is_array($this->media[$size])) {
            return $this->media[$size]['WIDTH'];
        }
        else {
            return null;
        }
    }


    function getURL($size = "original"){
        $ca_config = cawp_get_configuration();
        $url = null;

        if (is_array($this->media[$size])) {
            $url = $ca_config->get('ca_url') . '/' . $ca_config->get('ca_img_url_path') . '/' .
                $this->media[$size]['HASH'] . '/' . $this->media[$size]['MAGIC'] . '_' . $this->media[$size]['FILENAME'];
        }

        return $url;
    }

}