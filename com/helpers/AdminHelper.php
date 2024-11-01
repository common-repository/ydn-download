<?php
namespace ydn;
use \WP_Query;

class AdminHelper {

    public static function createAttrs($attrs) {
        $attrString = '';
        if(!empty($attrs) && isset($attrs)) {

            foreach ($attrs as $attrName => $attrValue) {
                $attrString .= ''.$attrName.'="'.$attrValue.'" ';
            }
        }

        return $attrString;
    }
	
	public static function getAllDefaultSettings() {
		$defaults = array();
		$defaults['textDecorationTypes'] = array(
			'underline' => __('Underline', YDN_TEXT_DOMAIN),
			'overline' => __('Overline', YDN_TEXT_DOMAIN),
			'line-through' => __('Line Through', YDN_TEXT_DOMAIN),
			'none' => __('None', YDN_TEXT_DOMAIN)
		);

		$defaults['dimensionsMode'] = array(
			'template' => array(
				'fieldWrapperAttr' => array(
					'class' => 'col-xs-5 ydn-choice-option-wrapper'
				),
				'labelAttr' => array(
					'class' => 'col-xs-5 ydn-choice-option-wrapper ydn-sub-option-label'
				),
				'groupWrapperAttr' => array(
					'class' => 'row form-group ydn-choice-wrapper ydn-choice-inputs-wrapper'
				)
			),
			'buttonPosition' => 'right',
			'nextNewLine' => true,
			'fields' => array(
				array(
					'attr' => array(
						'type' => 'radio',
						'name' => 'ydn-dimension-mode',
						'class' => 'dimension-mode',
						'data-attr-href' => 'dimension-mode-classic',
						'value' => 'classicMode'
					),
					'label' => array(
						'name' => __('Custom Dimensions', YDN_TEXT_DOMAIN).':'
					)
				),
				array(
					'attr' => array(
						'type' => 'radio',
						'name' => 'ydn-dimension-mode',
						'class' => 'dimension-mode',
						'data-attr-href' => 'dimension-mode-auto',
						'value' => 'autoMode'
					),
					'label' => array(
						'name' => __('Auto', YDN_TEXT_DOMAIN).':'
					)
				)
			)
		);
		
		$defaults['subscription-behavior'] =array(
			'template' => array(
				'fieldWrapperAttr' => array(
					'class' => 'col-md-6 ydn-choice-option-wrapper'
				),
				'labelAttr' => array(
					'class' => 'col-md-6 ydn-choice-option-wrapper'
				),
				'groupWrapperAttr' => array(
					'class' => 'row form-group ydn-choice-wrapper'
				)
			),
			'buttonPosition' => 'right',
			'nextNewLine' => true,
			'fields' => array(
				array(
					'attr' => array(
						'type' => 'radio',
						'name' => 'ydn-action-behavior',
						'class' => 'ydn-subscription-hide-behavior ydn-form-radio',
						'data-attr-href' => 'ydn-subscription-download',
						'value' => 'download'
					),
					'label' => array(
						'name' => __('Download', YDN_TEXT_DOMAIN)
					)
				)
			)
		);
		
		return apply_filters('ydnDefaults', $defaults);
	}

	public static function getCurrentPostType() {
		global $post_type;
		global $post;
		$currentPostType = '';

		if (is_object($post)) {
			$currentPostType = @$post->post_type;
		}

		// in some themes global $post returns null
		if (empty($currentPostType)) {
			$currentPostType = $post_type;
		}

		if (empty($currentPostType) && !empty($_GET['post'])) {
			$currentPostType = get_post_type($_GET['post']);
		}

		return $currentPostType;
	}

    public static function getIpAddress() {
        if (getenv('HTTP_CLIENT_IP'))
            $ipAddress = getenv('HTTP_CLIENT_IP');
        else if (getenv('HTTP_X_FORWARDED_FOR'))
            $ipAddress = getenv('HTTP_X_FORWARDED_FOR');
        else if (getenv('HTTP_X_FORWARDED'))
            $ipAddress = getenv('HTTP_X_FORWARDED');
        else if (getenv('HTTP_FORWARDED_FOR'))
            $ipAddress = getenv('HTTP_FORWARDED_FOR');
        else if (getenv('HTTP_FORWARDED'))
            $ipAddress = getenv('HTTP_FORWARDED');
        else if (getenv('REMOTE_ADDR'))
            $ipAddress = getenv('REMOTE_ADDR');
        else
            $ipAddress = 'UNKNOWN';

        return $ipAddress;
    }

    public static function getFormattedDate($date) {
        $date = strtotime($date);
        $month = date('F', $date);
        $year = date('Y', $date);

        return $month.' '.$year;
    }

    public static function createSelectBox($data, $selectedValue, $attrs) {
        $selected = '';
        $attrString = self::createAttrs($attrs);

        $selectBox = '<select '.$attrString.'>';

        foreach($data as $value => $label) {

            /*When is multiselect*/
            if(is_array($selectedValue)) {
                $isSelected = in_array($value, $selectedValue);
                if($isSelected) {
                    $selected = 'selected';
                }
            }
            else if($selectedValue == $value) {
                $selected = 'selected';
            }
            else if(is_array($value) && in_array($selectedValue, $value)) {
                $selected = 'selected';
            }

            $selectBox .= '<option value="'.$value.'" '.$selected.'>'.$label.'</option>';
            $selected = '';
        }

        $selectBox .= '</select>';

        return $selectBox;
    }

    public static function historyPageUrlByPostId($id = 0) {
        return admin_url('edit.php?post_type='.YDN_POST_TYPE.'&page='.YDN_HISTORY_PAGE.'&downloadId='.$id);
    }

    public static function filterHistory($query) {
        if (!empty($_GET['downloadId'])) {
            $query .= ' WHERE product_id ='.$_GET['downloadId'];
        }
        
        return $query;
    }
	
	public static function buildCreateDownloddUrl($type) {
		$isAvailable = $type->isAvailable();
		$name = $type->getName();
		
		$url = YDN_ADMIN_URL.'post-new.php?post_type='.YDN_POST_TYPE.'&ydn_type='.$name;
		
		if (!$isAvailable) {
			$url = YDN_PRO_URL;
		}
		
		return $url;
	}
	
	public static function getPluginActivationUrl($key) {
		$action = 'install-plugin';
		$contactFormUrl = wp_nonce_url(
			add_query_arg(
				array(
					'action' => $action,
					'plugin' => $key
				),
				admin_url( 'update.php' )
			),
			$action.'_'.$key
		);
		
		return $contactFormUrl;
	}
	
	public static function getCSSSafeSize($dimension) {
		if (empty($dimension)) {
			return 'inherit';
		}
		
		$size = (int)$dimension . 'px';
		// If user write dimension in px or % we give that dimension to target otherwise the default value will be px
		if (strpos($dimension, '%') || strpos($dimension, 'px')) {
			$size = $dimension;
		}
		
		return $size;
	}
	
	public static function jsonEncode($data) {
    	return json_encode($data, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT);
	}
	
	public static function conditionsKeys() {
		$keys = array(
			'select_settings' => __('Select settings', YDN_TEXT_DOMAIN),
			'devices' => __('Devices', YDN_TEXT_DOMAIN),
			'user_status' => __('User status', YDN_TEXT_DOMAIN),
			'countries' => __('Countries', YDN_TEXT_DOMAIN)
		);
		
		return $keys;
	}
	
	public static function getQueryDataByArgs($args = array()) {
		$defaultArgs = array(
			'offset'           =>  0,
			'orderby'          => 'date',
			'order'            => 'DESC',
			'post_status'      => 'publish',
			'suppress_filters' => true,
			'post_type'        => 'post',
			'posts_per_page'   => 1000
		);
		
		$args = wp_parse_args($args, $defaultArgs);
		$query = new WP_Query($args);
		
		return $query;
	}
	
	public static function getPostTypeData($args = array()) {
		$query = self::getQueryDataByArgs($args);
		
		$posts = array();
		foreach ($query->posts as $post) {
			$posts[$post->ID] = $post->post_title;
		}
		
		return $posts;
	}
	
	
	public static function selectBox($data, $selectedValue, $attrs) {
		
		$attrString = '';
		$selected = '';
		
		if(!empty($attrs) && isset($attrs)) {
			
			foreach ($attrs as $attrName => $attrValue) {
				$attrString .= ''.$attrName.'="'.$attrValue.'" ';
			}
		}
		
		$selectBox = '<select '.$attrString.'>';
		
		foreach ($data as $value => $label) {
			
			/*When is multiselect*/
			if(is_array($selectedValue)) {
				$isSelected = in_array($value, $selectedValue);
				if($isSelected) {
					$selected = 'selected';
				}
			}
			else if($selectedValue == $value) {
				$selected = 'selected';
			}
			else if(is_array($value) && in_array($selectedValue, $value)) {
				$selected = 'selected';
			}
			
			$selectBox .= '<option value="'.esc_attr($value).'" '.$selected.'>'.$label.'</option>';
			$selected = '';
		}
		
		$selectBox .= '</select>';
		
		return $selectBox;
	}

	public static function customizeSubsQuery($query = '') {

		global $wpdb;
		$subscribersTablename = $wpdb->prefix.YDN_SUBSCRIBERS_TABLE_NAME;
		$postsTablename = $wpdb->prefix.YDN_POSTS_TABLE_NAME;

		if ($query == '') {
			$query = 'SELECT email, cDate, '.$postsTablename.'.post_title AS type FROM '.$subscribersTablename.' ';
		}
		$searchQuery = '';
		$filterCriteria = '';

		$query .= ' LEFT JOIN '.$postsTablename.' ON '.$postsTablename.'.ID='.$subscribersTablename.'.subscriptionType';

		if (isset($_GET['ydn-subscription-id']) && !empty($_GET['ydn-subscription-id'])) {
			$filterCriteria = esc_sql($_GET['ydn-subscription-id']);
			if ($filterCriteria != 'all') {
				$searchQuery .= $subscribersTablename.".type = $filterCriteria";
			}
		}
		if ($filterCriteria != '' && $filterCriteria != 'all' && isset($_GET['s']) && !empty($_GET['s'])) {
			$searchQuery .= ' LIKE ';
		}
		if (isset($_GET['s']) && !empty($_GET['s'])) {
			$searchCriteria = esc_sql($_GET['s']);
			$searchQuery .= " email LIKE '%$searchCriteria%' or $postsTablename.post_title LIKE '%$searchCriteria%'";
		}
		if (isset($_GET['ydn-subscribers-dates']) && !empty($_GET['ydn-subscribers-dates'])) {
			$filterCriteria = esc_sql($_GET['ydn-subscribers-dates']);
			if ($filterCriteria != 'all') {
				if ($searchQuery != '') {
					$searchQuery .= ' AND ';
				}
				$searchQuery .= " cDate LIKE '$filterCriteria%'";
			}
		}
		if ($searchQuery != '') {
			$query .= " WHERE ($searchQuery)";
		}

		return $query;
	}

	public static function formattedDate($date) {
		$date = strtotime($date);
		$month = date('F', $date);
		$year = date('Y', $date);

		return $month.' '.$year;
	}
}