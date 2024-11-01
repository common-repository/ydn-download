<?php
namespace ydn;

class DownloadManager {
    private $typeObj;
    private $id;
    private $options;

    public function setTypeObj($typeObj) {
        $this->typeObj = $typeObj;
    }

    public function getTypeObj() {
        return $this->typeObj;
    }

    public function setOptions($options) {
        $this->options = $options;
    }

    public function getOptions() {
        return $this->options;
    }

    public function setId($id) {
        $this->id = (int)$id;
    }

    public function getId() {
        return (int)$this->id;
    }

    public static function findById($id) {
        global $wpdb;
        $query = $wpdb->prepare('SELECT * FROM '. $wpdb->prefix .YDN_DOWNLOADS_HISTORY.' WHERE id = %d', $id);
        $result = $wpdb->get_row($query, ARRAY_A);

        if (empty($result)) {
            return false;
        }
        $obj = new self();
        $obj->setId($id);
        $obj->setOptions($result);

        return $obj;
    }

    public static function downloadById($id)
    {
        $obj = Downloader::findById($id);

        if (empty($obj) || !is_object($obj)) {
            return '';
        }
        $currentObj = new self();

        $currentObj->setId($id);
        $currentObj->setTypeObj($obj);

        $currentObj->download();
    }

    private function addToStatistic() {
        global $wpdb;
        $typeObj = $this->getTypeObj();
        $productId = $this->getId();
        $version = $typeObj->getOptionValue('ydn-file-version');
        $label = $typeObj->getOptionValue('ydn-file-label');
        $ip = AdminHelper::getIpAddress();

        $query = $wpdb->prepare('INSERT INTO '. $wpdb->prefix .YDN_DOWNLOADS_HISTORY.' (`product_id`, `file_id`, `date`, `ip`, `file_label`, `version`, `options`) VALUES (%d, %d, %s, %s, %s, %s, %s)', $productId, 0, date('Y-m-d H:i'), $ip, $label, $version, '');
        $wpdb->query($query);
    }

    private function download() {
        $typeObj = $this->getTypeObj();
        $src = $typeObj->getOptionValue('ydn-target-link');
        $this->addToStatistic();

        header('Pragma: public');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Cache-Control: private', false);
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename='.wp_basename($src).';');
        header('Content-Transfer-Encoding: binary');
        readfile($src);

    }
}