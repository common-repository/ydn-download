<?php
namespace ydn;

class LinkDownloader extends Downloader {
    public function metaboxes() {
        parent::metaboxes();
        add_action('ydnAdditionalMetaboxes', array($this, 'additionalMetaboxes'), 100, 1);
    }

    public function additionalMetaboxes($metaboxes) {
        $metaboxes['ydnOtherConditionsMetaBoxView'] = array(
            'key' => 'ydnOtherConditionsMetaBoxView',
            'displayName' => 'Main Settings',
            'filePath' => YDN_TYPES_VIEWS_PATH.'linkSettings.php',
            'priority' => 'high'
        );

        return $metaboxes;
    }

    private function shortcodePage() {
        $id = $this->getId();
        $linkLabel = $this->getOptionValue('ydn-link-label');
		$style = $this->getShortcodeStyles();
		$attrTarget = '';
		$redirectToNewTab = $this->getOptionValue('ydn-open-downloads-page-new-tab');
		
		if (!empty($redirectToNewTab)) {
			$attrTarget = 'target="__blank"';
		}
		$content = $this->getBeforeContent();
	    $content .= '<a href="'.esc_attr(get_permalink($id)).'" class="ydn-download-link ydn-download-link-'.esc_attr($id).'" '.$attrTarget.'>'.$linkLabel.'</a>'.$style;
	    $content .= $this->getAfterContent();
	    
        return $content;
    }
    
    private function getShortcodeStyles() {
	    $id = $this->getId();
	    $textDecoration = $this->getOptionValue('ydn-text-decoration');
	   
	    $style = '<style>.ydn-download-link-'.$id.' {box-shadow: none !important;text-decoration: '.$textDecoration.' !important;}</style>';
	    
	    return $style;
    }

    public function jsFile($jsFiles) {
        $jsFiles[] = array('folderUrl' => YDN_FRONT_JS_URL, 'filename' => 'Downloader.js', 'dep' => array(), 'ver' => YDN_VERSION, 'inFooter' => false);

        return $jsFiles;
    }

    public function getViewContent() {
        $currentId = get_queried_object_id();
        $id = $this->getId();

        if ($currentId != $id) {
            // shorcode page
            return $this->shortcodePage();
        }
        else {
            return $this->downloadPage();
        }
    }

    public function includeScripts() {
        add_filter('ydnJsFiles', array($this, 'jsFile'), 1, 1);
    }
}