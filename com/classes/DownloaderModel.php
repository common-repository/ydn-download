<?php
namespace ydn;

class DownloaderModel {
    private static $data = array();

    private function __construct() {
    }

    public static function getDataById($postId) {
        if (!isset(self::$data[$postId])) {
            self::$data[$postId] = Downloader::getPostSavedData($postId);
        }

        return self::$data[$postId];
    }
}
