<?php
namespace ydn;

class Filters {
	public function __construct() {
		return $this->init();
	}

	public function init() {
		add_filter('admin_url', array($this, 'addNewPostUrl'), 10, 2);
		add_filter('the_content', array($this, 'content'));
        add_filter('manage_'.YDN_POST_TYPE.'_posts_columns', array($this, 'tableColumns'));
		add_filter('post_row_actions', array($this, 'duplicatePost'), 10, 2);
    }
	
	public function duplicatePost($actions, $post) {
		if (current_user_can('edit_posts') && $post->post_type == YDN_POST_TYPE) {
			$actions['duplicate'] = '<a href="' . wp_nonce_url('admin.php?action=ydn_duplicate_post_as_draft&post=' . $post->ID, YDN_POST_TYPE, 'duplicate_nonce' ) . '" title="Duplicate this item" rel="permalink">Clone</a>';
		}
		return $actions;
	}
    
    public function addNewPostUrl($url, $path) {
	    if ($path == 'post-new.php?post_type='.YDN_POST_TYPE) {
		    $url = str_replace('post-new.php?post_type='.YDN_POST_TYPE, 'edit.php?post_type='.YDN_POST_TYPE.'&page='.YDN_TYPES, $url);
	    }
	
	    return $url;
    }
    
    public function tableColumns($columns) {
        $additionalItems = array();
        $additionalItems['onOff'] = __('Enabled', YDN_TEXT_DOMAIN);
        $additionalItems['type'] = __('Type', YDN_TEXT_DOMAIN);
        $additionalItems['count'] = __('Count', YDN_TEXT_DOMAIN);
        $additionalItems['shortcode'] = __('Shortcode', YDN_TEXT_DOMAIN);

        return $columns + $additionalItems;
    }

	public function content($content) {
		$id = get_queried_object_id();
		$obj = Downloader::findById($id);
		if (!is_object($obj)) {
			return $content;
		}
        $content = $obj->getViewContent();

		return $content;
	}
}

new Filters();