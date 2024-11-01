<?php
namespace ydn;

class Actions {
    private $registerPostType;

    private $isLoadedMediaData = false;

    public function isLoadedMediaData() {
        return $this->isLoadedMediaData;
    }

    public function setIsLoadedMediaData($isLoadedMediaData) {
        $this->isLoadedMediaData = $isLoadedMediaData;
    }

    public function __construct() {
        $this->init();
    }

    private function init() {
        add_action('init', array($this, 'postType'));
        add_action('init', array($this, 'actionInit'));
        add_action('admin_head', array($this, 'adminHead'));
        add_action('save_post_'.YDN_POST_TYPE, array($this, 'save'), 10, 3);
        add_shortcode('ydn_downloader', array($this, 'downloaderShortcode'), 1);
        add_shortcode('ydn_download', array($this, 'ydnShortCode'), 1);
        add_action('wp_enqueue_scripts', array($this, 'wpScripts'), 59);
        add_action('admin_menu', array($this, 'addSubMenu'));

        add_action('admin_post_downloadId', array($this, 'downloadFile'), 59);
        add_action('admin_post_nopriv_downloadId', array($this, 'downloadFile'), 59);
        add_action('media_buttons', array($this, 'ydnMediaButton'), 11);
        add_action('widgets_init', array($this, 'loadWidgets'));
	    add_action('wp_enqueue_scripts', array($this, 'includeFrontJsFiles'), 100, 1);
	    add_action('admin_action_ydn_duplicate_post_as_draft', array($this, 'duplicatePostSave'));
    }
	
	public function duplicatePostSave() {
		
		global $wpdb;
		if (! ( isset( $_GET['post']) || isset( $_POST['post'])  || ( isset($_REQUEST['action']) && 'rd_duplicate_post_as_draft' == $_REQUEST['action'] ) ) ) {
			wp_die('No post to duplicate has been supplied!');
		}
		
		/*
		 * Nonce verification
		 */
		if (!isset($_GET['duplicate_nonce']) || !wp_verify_nonce($_GET['duplicate_nonce'], YDN_POST_TYPE))
			return;
		
		/*
		 * get the original post id
		 */
		$post_id = (isset($_GET['post']) ? absint( $_GET['post'] ) : absint( $_POST['post'] ) );
		/*
		 * and all the original post data then
		 */
		$post = get_post( $post_id );
		
		/*
		 * if you don't want current user to be the new post author,
		 * then change next couple of lines to this: $new_post_author = $post->post_author;
		 */
		$current_user = wp_get_current_user();
		$new_post_author = $current_user->ID;
		
		/*
		 * if post data exists, create the post duplicate
		 */
		if (isset( $post ) && $post != null) {
			
			/*
			 * new post data array
			 */
			$args = array(
				'comment_status' => $post->comment_status,
				'ping_status'    => $post->ping_status,
				'post_author'    => $new_post_author,
				'post_content'   => $post->post_content,
				'post_excerpt'   => $post->post_excerpt,
				'post_name'      => $post->post_name,
				'post_parent'    => $post->post_parent,
				'post_password'  => $post->post_password,
				'post_status'    => 'publish',
				'post_title'     => $post->post_title.'(clone)',
				'post_type'      => $post->post_type,
				'to_ping'        => $post->to_ping,
				'menu_order'     => $post->menu_order
			);
			
			/*
			 * insert the post by wp_insert_post() function
			 */
			$new_post_id = wp_insert_post( $args );
			
			/*
			 * get all current post terms ad set them to the new post draft
			 */
			$taxonomies = get_object_taxonomies($post->post_type); // returns array of taxonomy names for post type, ex array("category", "post_tag");
			foreach ($taxonomies as $taxonomy) {
				$post_terms = wp_get_object_terms($post_id, $taxonomy, array('fields' => 'slugs'));
				wp_set_object_terms($new_post_id, $post_terms, $taxonomy, false);
			}
			
			/*
			 * duplicate all post meta just in two SQL queries
			 */
			$post_meta_infos = $wpdb->get_results("SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id=$post_id");
			if (count($post_meta_infos)!=0) {
				$sql_query = "INSERT INTO $wpdb->postmeta (post_id, meta_key, meta_value) ";
				foreach ($post_meta_infos as $meta_info) {
					$meta_key = $meta_info->meta_key;
					if( $meta_key == '_wp_old_slug' ) continue;
					$meta_value = addslashes($meta_info->meta_value);
					$sql_query_sel[]= "SELECT $new_post_id, '$meta_key', '$meta_value'";
				}
				$sql_query.= implode(" UNION ALL ", $sql_query_sel);
				$wpdb->query($sql_query);
			}
			
			
			/*
			 * finally, redirect to the edit post screen for the new draft
			 */
			wp_redirect(admin_url('edit.php?post_type=' . YDN_POST_TYPE));
			exit;
		} else {
			wp_die('Post creation failed, could not find original post: ' . $post_id);
		}
	}
	
	public function includeFrontJsFiles() {
		ScriptsIncluder::registerScript('Validate.js', array('dirUrl' => YDN_FRONT_JS_URL));
		ScriptsIncluder::registerScript('Subscription.js', array('dirUrl' => YDN_FRONT_JS_URL));
	}

    public function loadWidgets() {
        register_widget('ydn_widget');
    }

    public function adminHead() {
        echo "<script>jQuery(document).ready(function() {jQuery('a[href*=\"page=ydn-support-page\"]').css({'color': 'yellow'});jQuery('a[href*=\"page=ydn-support-page\"]').bind('click', function(e) {e.preventDefault(); window.open('https://wordpress.org/support/plugin/ydn-download/')}) });</script>";
        echo "<script>jQuery(document).ready(function() {jQuery('a[href*=\"page=ydn-ideas-page\"]').css({'color': 'rgb(85, 239, 195)', 'font-size': '17px'});jQuery('a[href*=\"page=ydn-ideas-page\"]').bind('click', function(e) {e.preventDefault(); window.open('https://wordpress.org/support/plugin/ydn-download/')}) });</script>";
    }

    public function actionInit() {
        add_action('manage_'.YDN_POST_TYPE.'_posts_custom_column' , array($this, 'tableColumnsValues'), 10, 2);
    }

    public function tableColumnsValues($column, $postId)
    {
        $postId = (int)$postId;
        $checked = '';
        $downloaderObj = Downloader::findById($postId);

        $switchButton = '';
	    if ($column == 'type') {
	    	$type = $downloaderObj->getType();
	    	echo ucfirst($type);
	    }
        if ($column == 'onOff') {
            $postStatus = get_post_status($postId);
            if ($postStatus == 'publish' || $postStatus == 'draft') {
                $isActive = Downloader::isActivePost($postId);
                if($isActive) {
                    $checked = 'checked';
                }
            }
            $switchButton .= '<label class="ydn-switch">';
            $switchButton .= '<input class="ydn-switch-checkbox" data-switch-id="'.$postId.'" type="checkbox" '.$checked.'>';
            $switchButton .= '<div class="ydn-slider ydn-round"></div>';
            $switchButton .= '</label>';
            echo $switchButton;
        }
        if ($column == 'count') {
            $url = AdminHelper::historyPageUrlByPostId($postId);
            $count = Downloader::getCountById($postId);
            echo '<a href="'.esc_attr($url).'">'.$count.'</a>';
        }

        if ($column == 'shortcode') {
            echo '<div class="ydn-tooltip"><span class="ydn-tooltiptext" id="ydn-tooltip-'.$postId.'">'. __('Copy to clipboard', YDN_TEXT_DOMAIN).'</span><input type="text" class="download-shortcode" id="ydn-shortcode-input-'.$postId.'" data-id="'.$postId.'" value="[ydn_downloader id='.$postId.']" readonly="" onfocus="this.select()"></div>';
        }
    }

    function ydnMediaButton() {
        $this->setIsLoadedMediaData(true);
        new Tickbox();
    }

    public function addSubMenu() {
        $this->registerPostType->addSubMenu();
    }

    public function downloadFile() {
        $id = (int)$_GET['productId'];
        DownloadManager::downloadById($id);;
    }

    public function wpScripts() {
        ScriptsManager::includeScripts();
    }

    public function ydnShortCode($args) {
        $href = '';
        $title = 'Donwload';

        if(!empty($args['href'])) {
            $href = $args['href'];
        }

        if(!empty($args['title'])) {
            $title = $args['title'];
        }

        return '<a href="'.$href.'" download>'.$title.'</a>';
    }

    public function downloaderShortcode($args) {
        $obj = Downloader::findById($args['id']);

        if(empty($obj) || !is_object($obj)) {
            return '';
        }
        $allowToShow = $obj->allowToShow();

        if(empty($allowToShow)) {
            return '';
        }

        return $obj->getViewContent();
    }

    public function save($postId, $post, $update)
    {
        if (!$update) {
            return false;
        }
        $safePost = filter_input_array(INPUT_POST);
        $postData = Downloader::parseYdnDataFromData($safePost);
        $postData = apply_filters('ydnSavedData', $postData);
        if(empty($postData)) {
            return false;
        }
        $postData['ydn-post-id'] = $postId;
        $data = $postData;
        LinkDownloader::create($data);
    }

    public function ydnMetaboxes() {
        $additionalMetaboxes = apply_filters('ydnAdditionalMetaboxes', array());

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

	private function revieNotice() {
    		add_action('admin_notices', array($this, 'showReviewNotice'));
    		add_action('network_admin_notices', array($this, 'showReviewNotice'));
    		add_action('user_admin_notices', array($this, 'showReviewNotice'));
    }

	public function showReviewNotice() {
    		echo new YdnShowReviewNotice();
	}

    public function postType() {
        $this->registerPostType = new RegisterPostType();
        $this->revieNotice();
    }
}
