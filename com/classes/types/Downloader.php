<?php
namespace ydn;

abstract class Downloader {
    private $id;
    private $type;
    private $title;
    private $sanitizedData;
    private $savedData;
    private $metaboxes = array();
	
    public function addMetabox($metabox) {
    	if (!empty($metabox['key'])) {
		    $key = $metabox['key'];
		    $this->metaboxes[$key] = $metabox;
	    }
    }
    
    public function getMetaboxes() {
    	return $this->metaboxes;
    }
    
    public function __construct() {
        add_action('add_meta_boxes', array($this, 'ydnMetaboxes'), 100);
        $this->metaboxes();
    }

    public function metaboxes() {
	    $metabox = array(
		    'key' => 'ydnDownloadInformation',
		    'displayName' => 'Download Information',
		    'filePath' => YDN_VIEWS_PATH.'downloadInfo.php',
		    'context' => 'normal'
	    );
	    $this->addMetabox($metabox);
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

    public function setTitle($title) {
        $this->title = $title;
    }

    public function getTitle() {
        return $this->title;
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
        
        if (empty($type)) {
        	return '';
        }
        
        $className = self::getClassNameFromType($type);
        require_once(YDN_TYPES_CLASS_PATH.$className.'.php');
        $className = __NAMESPACE__.'\\'.$className;

        if (!class_exists($className)) {
            wp_die(__($className.' class does not exist', YDN_TEXT_DOMAIN));
        }
        $postTitle = get_the_title($id);

        $obj = new $className();
        $obj->setId($id);
        $obj->setType($type);
        $obj->setTitle($postTitle);

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
	    YdnOptionsConfig::optionsValues();

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
            case 'ydn':
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

    public static function getDownloadersObj($agrs = array()) {
        $postStatus = array('publish');
        $downloads = array();

        if (!empty($agrs['postStatus'])) {
            $postStatus = $agrs['postStatus'];
        }

        $posts = get_posts(array(
            'post_type' => YDN_POST_TYPE,
            'post_status' => $postStatus,
            'numberposts' => -1
            // 'order'	=> 'ASC'
        ));

        if(empty($posts)) {
            return $downloads;
        }

        foreach ($posts as $post) {
            $obj = self::findById($post->ID);

            if(empty($obj)) {
                continue;
            }
            $downloads[] = $obj;
        }

        return $downloads;
    }

    public static function shapeIdTitleData($downloads = false) {

        if (empty($downloads)) {
            $downloads = self::getDownloadersObj();
        }
        $idTitle = array();

        if(empty($downloads)) {
            return $idTitle;
        }

        foreach ($downloads as $currentDownload) {
            $title = $currentDownload->getTitle();
            $id = $currentDownload->getId();
//            $isActive = Countdown::isActivePost($id);
//
//            if(!$isActive) {
//                continue;
//            }
            if(empty($title)) {
                $title = __('(no title)', YDN_TEXT_DOMAIN);
            }

            $idTitle[$id] = $title .' - '.$currentDownload->getType();
        }

        return $idTitle;
    }

    public static function isActivePost($postId) {
        $enabled = !get_post_meta($postId, 'ydn_enable', true);
        $postStatus = get_post_status($postId);

        return ($enabled && $postStatus == 'publish');
    }
    
    private function getConditionsPath() {
    	$filePath =  YDN_ADMIN_VIEWS_PATH.'conditions.php';
    	
    	if (YDN_PKG_VERSION == YDN_FREE_VERSION) {
		    $filePath =  YDN_ADMIN_VIEWS_PATH.'freeConditions.php';
	    }
	    
	    return $filePath;
    }

    public function ydnMetaboxes() {
        $metaboxes = $this->getMetaboxes();
		$conditionsPath = $this->getConditionsPath();
		
        $metaboxes['ydnInformation'] = array(
            'key' => 'ydnInformation',
            'displayName' => 'Info',
            'filePath' => YDN_ADMIN_VIEWS_PATH.'information.php',
            'context' => 'side'
        );
        $metaboxes['ydnConditionsMetabox'] = array(
            'key' => 'ydnConditionsMetabox',
            'displayName' => 'Conditions',
            'filePath' => $conditionsPath
        );
        $metaboxes['ydnSupportMetabox'] = array(
            'key' => 'ydnSupportMetabox',
            'displayName' => 'Support',
            'filePath' => YDN_ADMIN_VIEWS_PATH.'support.php',
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

    public function allowToShow() {
        $id = $this->getId();
        $isActive = Downloader::isActivePost($id);
	
	    if(YDN_PKG_VERSION > YDN_FREE_VERSION) {
		    require_once YDN_CLASS_PATH.'Checker.php';
		    $obj = new Checker();
		    $obj->setObj($this);
		    $isAllow = $obj->isAllow();
		
		    if(!$isAllow) {
			    return $isAllow;
		    }
	    }

        return $isActive;
    }

    public static function getCountById($postId) {
        global $wpdb;
        $str = $wpdb->prepare('Select count(id) as count from '.$wpdb->prefix.YDN_DOWNLOADS_HISTORY.' where product_id=%d', $postId);
        $result = $wpdb->get_row($str, ARRAY_A);
        $count = (int)$result['count'];

        return $count;
    }
    
    public static function getDownloadTypesObj() {
	    global $YDN_TYPES;
	    $typesObj = array();
	    $downloadTypes = $YDN_TYPES['typeName'];
	
	    foreach ($downloadTypes as $downloadType => $level) {
		
		    if (empty($level)) {
			    $level = YDN_PKG_VERSION;
		    }
		
		    $downloadTypeObj = new DownloadType();
		    $downloadTypeObj->setName($downloadType);
		    $downloadTypeObj->setAccessLevel($level);
		
		    if (YDN_PKG_VERSION >= $level) {
			    $downloadTypeObj->setAvailable(true);
		    }
		    $typesObj[] = $downloadTypeObj;
	    }
	
	    return $typesObj;
    }
	
	public static function getClassNameDownloadType($type) {
		$typeName = ucfirst(strtolower($type));
		$className = $typeName.'Downloader';
		
		return $className;
	}
	
	public static function getTypePathFormScrollType($type) {
		global $YDN_TYPES;
		$typePath = '';
		
		if (!empty($YDN_TYPES['typePath'][$type])) {
			$typePath = $YDN_TYPES['typePath'][$type];
		}
		
		return $typePath;
	}
	
	public function getAllDataData() {
    	$dataName = array(
    		'ydn-action-behavior' => 'text'
	    );
    	$exportData = array();
    	
    	foreach ($dataName as $name => $type) {
		    $exportData[$name] = $this->getOptionValue($name);
	    }
	    
	    return $exportData;
	}
	
	public function downloadPage() {
		$id = $this->getId();
		$title = $this->getOptionValue('ydn-file-label');
		$src = $this->getOptionValue('ydn-target-link');
		$version = $this->getOptionValue('ydn-file-version');
		$versionLabel = $this->getOptionValue('ydn-file-version-label');
		$shortDescription = $this->getOptionValue('ydn-file-short-description');
		$shortDescriptionLabel = $this->getOptionValue('ydn-file-short-description-label');
		
		$url = add_query_arg(array(
			'action' => 'downloadId',
			'productId' => $id,
		), YDN_WP_ADMIN_POST_URL);
		
		$data = array(
			array('label' => 'File', 'value' => '<a href="'.esc_attr($url).'" class="ydn-download-link">'.$title.'</a>'),
			array('label' => $versionLabel, 'value' => $version),
			array('label' => $shortDescriptionLabel, 'value' => $shortDescription)
		);
		
		return FileDownloadPage::renderTable($data);
	}
	
	protected function getBeforeContent() {
    	$content = $this->getOptionValue('ydn-html-before-content');
    	$content = '<div class="ydn-before-content-wrapper">'.do_shortcode($content).'</div>';
    	$content = apply_filters('ydnBeforeContentHtml', $content);
    	
    	return $content;
	}
	
	protected function getAfterContent() {
    	$content = $this->getOptionValue('ydn-html-after-content');
    	$content = '<div class="ydn-after-content-wrapper">'.do_shortcode($content).'</div>';
    	$content = apply_filters('ydnAfterContentHtml', $content);
    	
    	return $content;
	}
}