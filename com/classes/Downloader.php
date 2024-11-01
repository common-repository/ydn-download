<?php
namespace ydn;

abstract class Downloader {
    private $id;
    private $type;
    private $sanitizedData;
    private $savedData;

    public function __construct() {
        add_action('add_meta_boxes', array($this, 'ydnMetaboxes'), 100);
        $this->metaboxes();
    }

    public function metaboxes() {

    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getId() {
        return $this->id;
    }

    public function setType($type) {
        $this->type = $type;
    }

    public function getType() {
        return $this->type;
    }

    public function setSavedData($savedData) {
        $this->savedData = $savedData;
    }

    public function getSavedData() {
        return $this->savedData;
    }

    public function insertIntoSanitizedData($sanitizedData) {
        if (!empty($sanitizedData)) {
            $this->sanitizedData[$sanitizedData['name']] = $sanitizedData['value'];
        }
    }

    public function getSanitizedData() {
        return $this->sanitizedData;
    }

    public static function parseYdnDataFromData($data) {
        $cdData = array();

        if(empty($data)) {
            return $cdData;
        }

        foreach ($data as $key => $value) {
            if (strpos($key, 'ydn') === 0) {
                $cdData[$key] = $value;
            }
        }

        return $cdData;
    }

    abstract public function getViewContent();

    public static function findById($id) {
        $savedData = DownloaderModel::getDataById($id);

        if(empty($savedData)) {
            return false;
        }

        $type = $savedData['ydn-type'];
        $className = self::getClassNameFromType($type);
        require_once(YDN_CLASS_PATH.$className.'.php');
        $className = __NAMESPACE__.'\\'.$className;

        if (!class_exists($className)) {
            wp_die(__($className.' class does not exist', YSTP_TEXT_DOMAIN));
        }

        $obj = new $className();
        $obj->setId($id);
        $obj->setType($type);

        return $obj;
    }

    public static function getClassNameFromType($type) {
        return ucfirst($type).substr(strrchr(__CLASS__, '\\'), 1);
    }

    public static function create($data = array()) {
        $obj = new static();
        $id = $data['ydn-post-id'];
        $obj->setId($id);
        // set up apply filter
        YndOptionsConfig::optionsValues();

        foreach ($data as $name => $value) {
            $defaultData = $obj->getDefaultDataByName($name);
            if (empty($defaultData['type'])) {
                $defaultData['type'] = 'string';
            }
            $sanitizedValue = $obj->sanitizeValueByType($value, $defaultData['type']);
            $obj->insertIntoSanitizedData(array('name' => $name,'value' => $sanitizedValue));
        }

        $obj->save();
    }

    public function sanitizeValueByType($value, $type) {
        switch ($type) {
            case 'string':
            case 'number':
                $sanitizedValue = sanitize_text_field($value);
                break;
            case 'html':
                $sanitizedValue = $value;
                break;
            case 'array':
                $sanitizedValue = $this->recursiveSanitizeTextField($value);
                break;
            case 'ystp':
                $sanitizedValue = $value;
                break;
            case 'email':
                $sanitizedValue = sanitize_email($value);
                break;
            case "checkbox":
                $sanitizedValue = sanitize_text_field($value);
                break;
            default:
                $sanitizedValue = sanitize_text_field($value);
                break;
        }

        return $sanitizedValue;
    }

    public function recursiveSanitizeTextField($array) {
        if (!is_array($array)) {
            return $array;
        }

        foreach ($array as $key => &$value) {
            if (is_array($value)) {
                $value = $this->recursiveSanitizeTextField($value);
            }
            else {
                /*get simple field type and do sensitization*/
                $defaultData = $this->getDefaultDataByName($key);
                if (empty($defaultData['type'])) {
                    $defaultData['type'] = 'string';
                }
                $value = $this->sanitizeValueByType($value, $defaultData['type']);
            }
        }

        return $array;
    }

    public function getDefaultDataByName($optionName) {
        global $YDN_OPTIONS;

        foreach ($YDN_OPTIONS as $option) {
            if ($option['name'] == $optionName) {
                return $option;
            }
        }

        return array();
    }

    public function save() {
        $options = $this->getSanitizedData();
        $options = apply_filters('ydnSavedOptions', $options);

        $postId = $this->getId();
        update_post_meta($postId, 'ydn_options', $options);
    }

    public function getOptionValue($optionName, $forceDefaultValue = false) {
        $savedData = DownloaderModel::getDataById($this->getId());
        $this->setSavedData($savedData);

        return $this->getOptionValueFromSavedData($optionName, $forceDefaultValue);
    }

    public static function getPostSavedData($postId) {
        $savedData = get_post_meta($postId, 'ydn_options');

        if (empty($savedData)) {
            return $savedData;
        }
        $savedData = $savedData[0];

        return $savedData;
    }

    public function getOptionValueFromSavedData($optionName, $forceDefaultValue = false) {

        $defaultData = $this->getDefaultDataByName($optionName);
        $savedData = $this->getSavedData();

        $optionValue = null;

        if (empty($defaultData['type'])) {
            $defaultData['type'] = 'string';
        }

        if (!empty($savedData)) { //edit mode
            if (isset($savedData[$optionName])) { //option exists in the database
                $optionValue = $savedData[$optionName];
            }
            /* if it's a checkbox, it may not exist in the db
             * if we don't care about it's existence, return empty string
             * otherwise, go for it's default value
             */
            else if ($defaultData['type'] == 'checkbox' && !$forceDefaultValue) {
                $optionValue = '';
            }
        }

        if (($optionValue === null && !empty($defaultData['defaultValue'])) || ($defaultData['type'] == 'number' && !isset($optionValue))) {
            $optionValue = $defaultData['defaultValue'];
        }

        if ($defaultData['type'] == 'checkbox') {
            $optionValue = $this->boolToChecked($optionValue);
        }

        if(isset($defaultData['ver']) && $defaultData['ver'] > YDN_PKG_VERSION) {
            if (empty($defaultData['allow'])) {
                return $defaultData['defaultValue'];
            }
            else if (!in_array($optionValue, $defaultData['allow'])) {
                return $defaultData['defaultValue'];
            }
        }

        return $optionValue;
    }

    public function boolToChecked($var) {
        return ($var ? 'checked' : '');
    }

    public function ydnMetaboxes() {
        $metaboxes = array();

        $metaboxes['ydnInformation'] = array(
            'key' => 'ydnInformation',
            'displayName' => 'Download Information',
            'filePath' => YDN_VIEWS_PATH.'information.php',
            'context' => 'side'
        );
        $additionalMetaboxes = apply_filters('ydnAdditionalMetaboxes', $metaboxes);

        if (empty($additionalMetaboxes)) {
            return false;
        }

        foreach ($additionalMetaboxes as $additionalMetabox) {
            if (empty($additionalMetabox)) {
                continue;
            }
            $context = 'normal';
            $priority = 'low';
            $filepath = $additionalMetabox['filePath'];
            // $popupTypeObj = $this->getPopupTypeObj();

            if (!empty($additionalMetabox['context'])) {
                $context = $additionalMetabox['context'];
            }
            if (!empty($additionalMetabox['priority'])) {
                $priority = $additionalMetabox['priority'];
            }

            add_meta_box(
                $additionalMetabox['key'],
                __($additionalMetabox['displayName'], YDN_TEXT_DOMAIN),
                function() use ($filepath) {
                    require_once $filepath;
                },
                YDN_POST_TYPE,
                $context,
                $priority
            );
        }
    }
}