<?php
namespace ydn;

class FormRenderHelper {
	
	public static function renderForm($formFields, $args = array()) {
		$form = '';
		
		if (empty($formFields) || !is_array($formFields)) {
			return $form;
		}
		$simpleElements = array(
			'text',
			'email',
			'password',
			'hidden',
			'submit',
			'button'
		);
        $id = $args['id'];
        $formAttrStr = '';
        
        if (!empty($args['savedData'])) {
            $formAttrStr .= 'data-saved-args = '. AdminHelper::jsonEncode($args['savedData']);
        }
		$form = '<form class="ydn-form" data-id="'.$id.'" id="ydn-form" method="POST" '.$formAttrStr.'>';
		$fields = '<div class="ydn-form-wrapper">';
		foreach ($formFields as $fieldKey => $formField) {
			$params = $formField;
			$htmlElement = '';
			$hideClassName = '';
			$type = 'text';
			
			if (!empty($formField['attrs']['type'])) {
				$type = $formField['attrs']['type'];
				if ($type == 'customCheckbox') {
					$formField['attrs']['type'] = 'checkbox';
				}
			}
			
			$styles = '';
			$attrs = '';
			$label = '';
			$gdprWrapperStyles = '';
			$gdprText = '';
			$errorMessageBoxStyles = '';
			
			if (!isset($formField['attrs']['name']) || $formField['attrs']['name'] == '') {
				continue;
			}
			$errorWrapperClassName = @$formField['attrs']['name'].'-error-message';
			if (isset($formField['errorMessageBoxStyles'])) {
				$errorMessageBoxStyles = 'style="width:'.$formField['errorMessageBoxStyles'].'"';
			}
			if (!empty($formField['label'])) {
				$label = $formField['label'];
				if (isset($formField['text'])) {
					$gdprText = $formField['text'];
				}
				$formField['style'] = array('color' => @$formField['style']['color'], 'width' => $formField['style']['width']);
				$gdprWrapperStyles = 'style="color:'.$formField['style']['color'].'"';
			}
			
			if ($type == 'checkbox') {
				$formField['style']['max-width'] = $formField['style']['width'];
				unset($formField['style']['width']);
			}
			if (!empty($formField['style'])) {
				$styles = 'style="';
				if (strpos(@$formField['attrs']['name'], 'gdpr') !== false) {
					unset($formField['style']['height']);
				}
				foreach ($formField['style'] as $styleKey => $styleValue) {
					if ($styleKey == 'placeholder') {
						$styles .= '';
					}
					$styles .= $styleKey.':'.$styleValue.'; ';
				}
				$styles .= '"';
			}
			
			if (!empty($formField['attrs'])) {
				foreach ($formField['attrs'] as $attrKey => $attrValue) {
					$attrs .= $attrKey.' = "'.esc_attr($attrValue).'" ';
				}
			}
			
			if (isset($formField['isShow']) && !$formField['isShow']) {
				$hideClassName = 'ydn-js-hide';
			}
			
			if (in_array($type, $simpleElements)) {
				if (!isset($formField['attrs']['hasLabel']) || !$formField['attrs']['hasLabel']) {
					$params = array();
				}
				$htmlElement = self::createInputElement($attrs, $styles, $errorWrapperClassName, $errorMessageBoxStyles, $params);
			}
			else if ($type == 'checkbox') {
				$htmlElement = self::createCheckbox($attrs, $styles);
				
			}
			else if ($type == 'customCheckbox') {
				$label = $formField['label'];
				if (isset($formField['text'])) {
					$gdprText = $formField['text'];
				}
				$formField['style'] = array('color' => @$formField['style']['color'], 'width' => @$formField['style']['width']);
				$gdprWrapperStyles = 'style="color:'.@$formField['style']['color'].'"';
				$htmlElement = self::createGdprCheckbox($attrs, $styles, $label, $gdprWrapperStyles, $gdprText);
			}
			else if ($type == 'textarea') {
				$htmlElement = self::createTextArea($attrs, $styles, $errorWrapperClassName);
			}
			
			ob_start();
			?>
			<div class="ydn-inputs-wrapper js-<?php echo $fieldKey; ?>-wrapper js-ydn-form-field-<?php echo $fieldKey; ?>-wrapper <?php echo $hideClassName; ?>">
				<?php echo $htmlElement; ?>
			</div>
			<?php
			$fields .= ob_get_contents();
			ob_get_clean();
		}
		$fields .= '</div>';
		
		$form .= $fields;
		$form .= '</form>';
		
		return $form;
	}
	
	public static function createInputElement($attrs, $styles = '', $errorWrapperClassName = '', $errorMessageBoxStyles = '', $labelArgs = array()) {
		$inputElement = "<input $attrs $styles>";
		if (!empty($labelArgs)) {
			$inputElement = '<label for="'.@$labelArgs['attrs']['ydn-login-username'].'"><p class="ydn-login-input-label '.@$labelArgs['attrs']['labelClass'].'">'.@$labelArgs['attrs']['hasLabel'].'</p>'.$inputElement.'</label>';
		}
		if (!empty($errorWrapperClassName)) {
			$inputElement .= "<div class='$errorWrapperClassName'></div>";
		}
		
		return $inputElement;
	}
	
	public static function createCheckbox($attrs, $styles) {
		$inputElement = "<input $attrs $styles>";
		
		return $inputElement;
	}
	
	public static function createGdprCheckbox($attrs, $styles, $label = '', $gdprWrapperStyles = '', $text = '') {
		$inputElement = "<input $attrs>";
		$inputElement = '<div class="ydn-gdpr-label-wrapper" '.$styles.'>'.$inputElement.'<label class="js-login-remember-me-label-edit" for="ydn-gdpr-field-label">'.$label.'</label><div class="ydn-gdpr-error-message"></div></div>';
		if ($text == '') {
			return $inputElement;
		}
		$text = html_entity_decode($text);
		$inputElement .= '<div class="ydn-alert-info ydn-alert ydn-gdpr-info js-subs-text-checkbox ydn-gdpr-text-js" '.$styles.'>'.$text.'</div>';
		
		return $inputElement;
	}
	
	public static function createTextArea($attrs, $styles, $errorWrapperClassName = '') {
		$inputElement = "<textarea $attrs $styles></textarea>";
		if (!empty($errorWrapperClassName)) {
			$inputElement .= "<div class='$errorWrapperClassName'></div>";
		}
		
		return $inputElement;
	}
}