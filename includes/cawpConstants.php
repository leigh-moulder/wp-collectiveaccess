<?php

/**
 * This class defines a set of constants that are used throughout the
 * Collective Access system.
 */
if (!class_exists('cawpConstants')) {
    class cawpConstants {

        // database constants
        const OBJECT_TYPES = "object_types";
        const OBJECT_SOURCES = "object_sources";

        // image constants
        const IMAGE_PREVIEW = "preview";
        const IMAGE_PRIMARY = "return_primary_only";
        const IMAGE_SMALL = "small";
        const IMAGE_MEDIUM = "medium";
    }

}