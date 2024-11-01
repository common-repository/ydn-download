<?php
namespace ydn;

class ButtonDownloader extends Downloader {
	public function metaboxes() {
		parent::metaboxes();
		add_action('ydnAdditionalMetaboxes', array($this, 'additionalMetaboxes'), 100, 1);
	}
	
	public function additionalMetaboxes($metaboxes) {
		$metaboxes['ydnOtherConditionsMetaBoxView'] = array(
			'key' => 'ydnOtherConditionsMetaBoxView',
			'displayName' => 'Main Settings',
			'filePath' => YDN_TYPES_VIEWS_PATH.'buttonSettings.php',
			'priority' => 'high'
		);
		
		return $metaboxes;
	}
	
	private function shortcodePage() {
		$id = $this->getId();
		$linkLabel = $this->getOptionValue('ydn-button-label');
		$style = $this->getShortcodeStyles();
		
		$content = $this->getBeforeContent();
		$content .= '<a href="'.esc_attr(get_permalink($id)).'"><button class="ydn-download-link ydn-download-link-'.esc_attr($id).'">'.$linkLabel.'</button></a>'.$style;
		$content .= $this->getAfterContent();
		
		return $content;
	}
	
	private function getShortcodeStyles() {
		$id = $this->getId();
		
		$style = '<style>';
		$dimension = $this->getOptionValue('ydn-button-custom-dimension');
		if ($dimension) {
			$dimensionMode = $this->getOptionValue('ydn-dimension-mode');
			if ($dimensionMode == 'classicMode') {
				$width = $this->getOptionValue('ydn-button-width');
				$height = $this->getOptionValue('ydn-button-height');
				$style .= '.ydn-download-link-'.$id ." {width: $width; height: $height;}";
			}
			else if ($dimensionMode == 'autoMode'){
				$top = $this->getOptionValue('ydn-button-padding-top');
				$right = $this->getOptionValue('ydn-button-padding-right');
				$bottom = $this->getOptionValue('ydn-button-padding-bottom');
				$left = $this->getOptionValue('ydn-button-padding-bottom');
				$style .= '.ydn-download-link-'.$id ." {padding: ".$top." ".$right." ".$bottom." ".$left."}";
			}
		}
		$style .= '<style>';
		
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