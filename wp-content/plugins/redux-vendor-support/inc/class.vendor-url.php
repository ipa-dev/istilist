<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if (!class_exists('Redux_VendorURL')) {
    class Redux_VendorURL {
        static public $url;
        static public $dir;
    
        public static function get_url($handle) {
            if ($handle == 'ace-editor-js') {
                return self::$url . 'vendor/ace/ace.js';
            } elseif ($handle == 'select2-js') {
                return self::$url . 'vendor/select2/select2.js';
            } elseif ($handle == 'select2-css') {
                return self::$url . 'vendor/select2/select2.css';
            } elseif ($handle == 'qtip-css') {
                return self::$url . 'vendor/qtip/jquery.qtip.css';
            } elseif ($handle == 'qtip-js') {
                return self::$url . 'vendor/qtip/jquery.qtip.js';
            } elseif ($handle == 'redux-spectrum-js') {
                return self::$url . 'vendor/spectrum/redux-spectrum.js';
            } elseif ($handle == 'redux-spectrum-css') {
                return self::$url . 'vendor/spectrum/redux-spectrum.css';
            }
        }
    }
}