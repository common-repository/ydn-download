<?php
namespace ydn;

class SubscriptionDownloader extends Downloader {
	
	public function __construct() {
		parent::__construct();
	}
	
	public function metaboxes() {
		parent::metaboxes();
		add_action('ydnAdditionalMetaboxes', array($this, 'additionalMetaboxes'), 100, 1);
	}
	
	private function fields() {
		$inputStyles = array();
		$submitStyles = array();
		if (1)  {
			$inputWidth = '300px';
			$inputStyles['width'] = AdminHelper::getCSSSafeSize($inputWidth);
		}
		if (1) {
			$inputHeight = '40px';
			$inputStyles['height'] = AdminHelper::getCSSSafeSize($inputHeight);
		}
		if (1) {
			$inputBorderWidth = '1px';
			$inputStyles['border-width'] = AdminHelper::getCSSSafeSize($inputBorderWidth);
		}
		if (1) {
			$inputStyles['border-color'] = '#CCCCCC';
		}
		if (1) {
			$inputStyles['background-color'] = '#FFFFFF';
		}
		if (1) {
			$inputStyles['color'] = '#000000';
		}
		$inputStyles['autocomplete'] = 'off';
		$inputStyles['border-style'] = 'solid';
		
		if (1)  {
			$inputWidth = '300px';
			$submitStyles['width'] = AdminHelper::getCSSSafeSize($inputWidth);
		}
		if (1) {
			$inputHeight = '40px';
			$submitStyles['height'] = AdminHelper::getCSSSafeSize($inputHeight);
		}
		if (1) {
			$inputBorderWidth = '0px';
			$submitStyles['border-width'] = AdminHelper::getCSSSafeSize($inputBorderWidth);
		}
		if (1) {
			$submitStyles['background-color'] = '#2d74b9';
		}
		if (1) {
			$submitStyles['color'] = '#FFFFFF';
		}
		if (1) {
			$submitStyles['border-radius'] = AdminHelper::getCSSSafeSize('0px');
		}
		if (1) {
			$submitStyles['border-width'] = AdminHelper::getCSSSafeSize('0px');
		}
		if (1) {
			$submitStyles['border-color'] = '#4CAF50';
		}
		$submitStyles['autocomplete'] = 'off';
		$submitStyles['border-style'] = 'solid';
		
		$formData = array();
		$isShow = $this->getOptionValue('ydn-subs-first-name') ? true : false;
		$firstNameRequired = $this->getOptionValue('ydn-subs-first-name-required') ? true : false;
		$firstNamePlaceholder = $this->getOptionValue('ydn-subs-first-name-placeholder');
		$emailPlaceholder = $this->getOptionValue('ydn-subs-email-placeholder');
		$formData['email'] = array(
			'isShow' => $isShow,
			'attrs' => array(
				'type' => 'email',
				'data-required' => true,
				'name' => 'ydn-subs-email',
				'placeholder' => $emailPlaceholder,
				'class' => 'js-subs-text-inputs js-subs-email-input',
				'data-error-message-class' => 'ydn-subs-email-error-message'
			),
			'style' => $inputStyles,
			'errorMessageBoxStyles' => ''
		);
		
		$formData['first-name'] = array(
			'isShow' => $isShow,
			'attrs' => array(
				'type' => 'text',
				'data-required' => $firstNameRequired,
				'name' => 'ydn-subs-first-name',
				'placeholder' => $firstNamePlaceholder,
				'class' => 'js-subs-text-inputs js-subs-first-name-input',
				'data-error-message-class' => 'ydn-subs-first-name-error-message'
			),
			'style' => $inputStyles,
			'errorMessageBoxStyles' => ''
		);
		
		$isShow = $this->getOptionValue('ydn-subs-last-name') ? true : false;
		$lastNameRequired = $this->getOptionValue('ydn-subs-last-name-required') ? true : false;
		$lastNamePlaceholder = $this->getOptionValue('ydn-subs-last-name-placeholder');
		
		$formData['last-name'] = array(
			'isShow' => $isShow,
			'attrs' => array(
				'type' => 'text',
				'data-required' => $lastNameRequired,
				'name' => 'ydn-subs-last-name',
				'placeholder' => $lastNamePlaceholder,
				'class' => 'js-subs-text-inputs js-subs-last-name-input',
				'data-error-message-class' => 'ydn-subs-last-name-error-message'
			),
			'style' => $inputStyles,
			'errorMessageBoxStyles' => ''
		);
		
		/* GDPR checkbox */
	
		/* GDPR checkbox */
		
		$hiddenChecker['position'] = 'absolute';
		// For protected bots and spams
		$hiddenChecker['left'] = '-5000px';
		$hiddenChecker['padding'] = '0';
		$formData['hidden-checker'] = array(
			'isShow' => false,
			'attrs' => array(
				'type' => 'hidden',
				'data-required' => false,
				'name' => 'ydn-subs-hidden-checker',
				'value' => '',
				'class' => 'js-subs-text-inputs js-subs-last-name-input'
			),
			'style' => $hiddenChecker
		);
		
		$submitTitle = $this->getOptionValue('ydn-subs-btn-title');
		$progressTitle = $this->getOptionValue('ydn-subs-btn-title-progress');
		$formData['submit'] = array(
			'isShow' => true,
			'attrs' => array(
				'type' => 'submit',
				'name' => 'ydn-subs-submit',
				'value' => $submitTitle,
				'data-title' => $submitTitle,
				'data-progress-title' => $progressTitle,
				'class' => 'js-subs-submit-btn'
			),
			'style' => $submitStyles
		);
		
		return $formData;
	}
	
	public function additionalMetaboxes($metaboxes) {
		$metaboxes['ydnOtherConditionsMetaBoxView'] = array(
			'key' => 'ydnOtherConditionsMetaBoxView',
			'displayName' => 'Main Settings',
			'filePath' => YDN_TYPES_VIEWS_PATH.'subscriptionOptions.php',
			'priority' => 'high'
		);
		
		return $metaboxes;
	}
	
	private function getFormMessages() {
		return '';
	}
	
	public function getValidateObj() {
		$requiredMessage = $this->getOptionValue('ydn-subs-required-field');
		$emailMessage = $this->getOptionValue('ydn-subs-invalid-email');
		
		$fields = $this->fields();
		$id = $this->getId();
		$rules = 'rules: { ';
		$messages = 'messages: { ';
		
		$validateObj = 'var ydnSubsObj'.$id.' = { ';
		foreach ($fields as $subsField) {
			
			if (empty($subsField['attrs'])) {
				continue;
			}
			
			$attrs = $subsField['attrs'];
			$type = 'text';
			$name = '';
			$required = false;
			
			if (!empty($attrs['type'])) {
				$type = $attrs['type'];
			}
			if (!empty($attrs['name'])) {
				$name = $attrs['name'];
			}
			if (!empty($attrs['data-required'])) {
				$required = $attrs['data-required'];
			}
			
			if ($type == 'email') {
				$rules .= '"'.$name.'": {required: true, email: true},';
				$messages .= '"'.$name.'": {
					"required": "'.$requiredMessage.'",
					"email": "'.$emailMessage.'"
				},';
				continue;
			}
			
			if (!$required) {
				continue;
			}
			
			$messages .= '"'.$name.'": "'.$requiredMessage.'",';
			$rules .= '"'.$name.'" : "required",';
			
		}
		$rules = rtrim($rules, ',');
		$messages = rtrim($messages, ',');
		
		$rules .= '},';
		$messages .= '}';
		
		$validateObj .= $rules;
		$validateObj .= $messages;
		
		$validateObj .= '};';

		return $validateObj;
	}
	
	private function getSubscriptionValidationScripts($validateObj) {
		$script = '<script type="text/javascript">';
		$script .= $validateObj;
		$script .= '</script>';
		
		return $script;
	}
	
	private function getSubscriptionForm($subsFields) {
		$id = $this->getId();
		$args = array('id' => $id);
		$validateObj = $this->getValidateObj();

		$data = $this->getAllDataData();
		$args['savedData'] = $data;
		$form = "<div class='ydn-subs-form-$id ydn-subscription-form'>";
		$form .= $this->getFormMessages();
		$form .= FormRenderHelper::renderForm($subsFields, $args);
		$form .= $this->getSubscriptionValidationScripts($validateObj);
		$form .= '</div>';
		
		return $form;
	}
	
	private function includeMedia() {
		$id = $this->getId();
		wp_enqueue_script('jquery');
		ScriptsIncluder::loadStyle('ResetFormStyle.css', array('dirUrl' => YDN_FRONT_CSS_URL));
		ScriptsIncluder::loadStyle('Subscription.css', array('dirUrl' => YDN_FRONT_CSS_URL));
		ScriptsIncluder::enqueueScript('Validate.js');
		ScriptsIncluder::registerScript('Subscription.js', array('dirUrl' => YDN_FRONT_JS_URL));
		$localizedData = array(
			'ajaxurl' => admin_url('admin-ajax.php'),
			'nonce' => wp_create_nonce(YDN_DOWNLOADS_NONCE),
			'downloadPostURL' => esc_attr(get_permalink($id))
		);
		
		ScriptsIncluder::localizeScript('Subscription.js', 'YDN_ARGS', $localizedData);
		ScriptsIncluder::enqueueScript('Subscription.js');
	}
	
	public function shortcodePage() {
		$formFields = $this->fields();
		$this->includeMedia();
		
		$content = $this->getBeforeContent();
		$content .= $this->getSubscriptionForm($formFields);
		$content .= $this->getAfterContent();
		
		return $content;
	}
	
	public function getViewContent() {
		
		$currentId = get_queried_object_id();
		$id = $this->getId();
		
		if ($currentId != $id) {
			// shorcode page
			return $this->shortcodePage();
		}
		
		
		return $this->downloadPage();
	}
	
	public function includeScripts() {
		
		add_filter('ydnJsFiles', array($this, 'jsFile'), 1, 1);
	}
	
	public function jsFile($jsFiles) {
		$jsFiles[] = array('folderUrl' => YDN_FRONT_JS_URL, 'filename' => 'Validate.js', 'dep' => array(), 'ver' => YDN_VERSION, 'inFooter' => false);
		$jsFiles[] = array('folderUrl' => YDN_FRONT_JS_URL, 'filename' => 'Downloader.js', 'dep' => array(), 'ver' => YDN_VERSION, 'inFooter' => false);
		
		return $jsFiles;
	}
}