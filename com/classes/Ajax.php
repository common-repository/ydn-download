<?php
namespace ydn;
use \DateTime;

class Ajax {
	private $postData;
	
    public function __construct() {
        $this->init();
    }
	
	public function setPostData($postData) {
		$this->postData = $postData;
	}
	
	public function getPostData() {
		return $this->postData;
	}
	
	public function getValueFromPost($key) {
		$postData = $this->getPostData();
		$value = '';
		
		if (!empty($postData[$key])) {
			$value = $postData[$key];
		}
		
		return $value;
	}

    private function init() {
        add_action('wp_ajax_ydn_datatable_delete', array($this, 'deleteHistory'));
        add_action('wp_ajax_ydn-switch', array($this, 'switchEnable'));

        // review panel
        add_action('wp_ajax_ydn_dont_show_review_notice', array($this, 'dontShowReview'));
        add_action('wp_ajax_ydn_change_review_show_period', array($this, 'changeReviewPeriod'));
        
        add_action('wp_ajax_ydn_subscribe', array($this, 'subscribe'));
        add_action('wp_ajax_nopriv_ydn_subscribe', array($this, 'subscribe'));
	    add_action('wp_ajax_ydn_edit_conditions_row', array($this, 'conditionsRow'));
	    add_action('wp_ajax_ydn_add_conditions_row', array($this, 'conditionsRow'));
    }
	
	public function conditionsRow() {
		check_ajax_referer('ydn_ajax_nonce', 'nonce');

		$selectedParams = sanitize_text_field($_POST['selectedParams']);
		$conditionId = (int)$_POST['conditionId'];
		$childClassName = $_POST['conditionsClassName'];
		$childClassName = __NAMESPACE__.'\\'.$childClassName;
		$obj = new $childClassName();
		
		echo $obj->renderConditionRowFromParam($selectedParams, $conditionId);
		wp_die();
	}
    
    public function subscribe() {
	    check_ajax_referer(YDN_DOWNLOADS_NONCE, 'nonce');
	    parse_str($_POST['formData'], $formData);
	
	    $this->setPostData($_POST);
	    $submissionData = $this->getValueFromPost('formData');
	    $postId = (int)$this->getValueFromPost('postId');
	
	    parse_str($submissionData, $formData);
	    $responseArgs = array('status' => YDN_AJAX_STATUS_TRUE, 'message' => '');
	    
	    if (empty($formData)) {
		    $responseArgs['status'] = false;
		    echo json_encode($responseArgs);
		    wp_die();
	    }
	
	    $hiddenChecker = sanitize_text_field($formData['ydn-subs-hidden-checker']);
	    
	    // this check is made to protect ourselves from bot
	    if (!empty($hiddenChecker)) {
		    $responseArgs = array('status' => YDN_AJAX_STATUS_FALSE, 'message' => 'Bot');
		    echo json_encode($responseArgs);
		    wp_die();
	    }
	    global $wpdb;
	
	    
	    $date = date('Y-m-d');
	    $email = sanitize_email($formData['ydn-subs-email']);
	    if (empty($email)) {
		    $responseArgs = array('status' => YDN_AJAX_STATUS_FALSE, 'message' => 'Invalid Email address');
		    echo json_encode($responseArgs);
		    wp_die();
	    }
	    $firstName = sanitize_text_field($formData['ydn-subs-first-name']);
	    $lastName = sanitize_text_field($formData['ydn-subs-last-name']);
	
	    $subscribersTableName = $wpdb->prefix.YDN_SUBSCRIBERS_TABLE_NAME;
	
	    $getSubscriberQuery = $wpdb->prepare('SELECT id FROM '.$subscribersTableName.' WHERE email = %s AND subscriptionType = %d', $email, $postId);
	    $list = $wpdb->get_row($getSubscriberQuery, ARRAY_A);
	
	    // When subscriber does not exist we insert to subscribers table otherwise we update user info
	    if (empty($list['id'])) {
		    $sql = $wpdb->prepare('INSERT INTO '.$subscribersTableName.' (firstName, lastName, email, cDate, subscriptionType) VALUES (%s, %s, %s, %s, %d) ', $firstName, $lastName, $email, $date, $postId);
		    $res = $wpdb->query($sql);
	    }
	    else {
		    $sql = $wpdb->prepare('UPDATE '.$subscribersTableName.' SET firstName = %s, lastName = %s, email = %s, cDate = %s, subscriptionType = %d WHERE id = %d', $firstName, $lastName, $email, $date, $postId, $list['id']);
		    $wpdb->query($sql);
		    $res = 1;
	    }
	    if (!$res) {
			 $responseArgs['status'] = (bool)$res;
	    }
	
	    echo json_encode($responseArgs);
    	die;
    }

	public function changeReviewPeriod() {
        check_ajax_referer('ydnReviewNotice', 'ajaxNonce');
        $messageType = sanitize_text_field($_POST['messageType']);

        $timeDate = new DateTime('now');
        $timeDate->modify('+'.YDN_SHOW_REVIEW_PERIOD.' day');

        $timeNow = strtotime($timeDate->format('Y-m-d H:i:s'));
        update_option('YdnShowNextTime', $timeNow);
        $usageDays = get_option('YdnUsageDays');
        $usageDays += YDN_SHOW_REVIEW_PERIOD;
        update_option('YdnUsageDays', $usageDays);

        echo YDN_AJAX_SUCCESS;
		wp_die();
	}

	public function dontShowReview() {
        check_ajax_referer('ydnReviewNotice', 'ajaxNonce');
        update_option('YdnDontShowReviewNotice', 1);

        echo YCD_AJAX_SUCCESS;
		wp_die();
 	}

    public function switchEnable() {
        check_ajax_referer('ydn_ajax_nonce', 'nonce');
        $postId = (int)$_POST['id'];
        $checked = $_POST['checked'] == 'true' ? '' : true;

        update_post_meta($postId, 'ydn_enable', $checked);

        echo 1;
        wp_die();
    }

    public function deleteHistory() {
        check_ajax_referer('ydn_ajax_nonce', 'nonce');
        $itemsId = $_POST['itemsId'];

        if (empty($itemsId)) {
            echo YDN_AJAX_FALSE;
            wp_die();
        }
	    $tableName = $_POST['tableName'];
        global $wpdb;

        $values = array_values($itemsId);
        $filteredValues = array_filter($values, array($this, 'ydnParseIntValue'));
        $idsString = implode(', ', $filteredValues);
        $query = $wpdb->query('DELETE FROM '.$tableName.' WHERE id IN ('.$idsString.')');

        echo YDN_AJAX_TRUE;
        wp_die();
    }

    public function ydnParseIntValue($value) {
        return (int)$value;
    }
}

new Ajax();