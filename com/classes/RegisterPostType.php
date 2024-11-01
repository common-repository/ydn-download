<?php
namespace ydn;

class RegisterPostType {
    private $typeObj;
    private $type;
    private $id;

    public function __construct() {
        $this->init();

        return true;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getId() {
        return (int)$this->id;
    }

    public function setType($type) {
        $this->type = $type;
    }

    public function getType() {
        return $this->type;
    }

    public function setTypeObj($typeObj) {
        $this->typeObj = $typeObj;
    }

    public function getTypeObj() {
        return $this->typeObj;
    }

    public function init() {
        $postType = YDN_POST_TYPE;

        $args = $this->getPostTypeArgs();

        register_post_type($postType, $args);
        flush_rewrite_rules(false);

        $this->createDownloadTypeObj();
        $this->registerTaxonomy();
    }

    private function createDownloadTypeObj() {
        $id = 0;
        if(!empty($_GET['post'])) {
	        $id = $_GET['post'];
        }
        
	    $type = $this->getTypeName();
	    $this->setType($type);
	    $this->setId($id);
	
	    $this->createCdObj();
    }
    
    private function createCdObj() {
    	
	    $id = $this->getId();
	    $type = $this->getType();
	    
	    $typePath = Downloader::getTypePathFormScrollType($type);
	    $className = Downloader::getClassNameDownloadType($type);
	
	    if (!file_exists($typePath.$className.'.php')) {
		    wp_die(__($className.' class does not exist', YDN_TEXT_DOMAIN));
	    }
	    require_once($typePath.$className.'.php');
	    $className = __NAMESPACE__.'\\'.$className;
	
	    $typeObj = new $className();
	    $typeObj->setId($id);
	    $typeObj->setType($type);
	    $this->setTypeObj($typeObj);
    }
	
	private function getTypeName() {
		$type = YDN_DEFAULT_TYPE;
		
		/*
		 * First, we try to find the Scroll type with the post id then,
		 * if the post id doesn't exist, we try to find it with $_GET['ystp_type']
		 */
		if (!empty($_GET['post'])) {
			$id = (int)$_GET['post'];
			$cdOptionsData = Downloader::getPostSavedData($id);
			if (!empty($cdOptionsData['ydn-type'])) {
				$type = $cdOptionsData['ydn-type'];
			}
		}
		else if (!empty($_GET['ydn_type'])) {
			$type = $_GET['ydn_type'];
		}
		
		return $type;
	}

    public function getPostTypeArgs()
    {
        $labels = $this->getPostTypeLabels();

        $args = array(
            'labels'             => $labels,
            'description'        => __('Description.', YDN_TEXT_DOMAIN),
            //Exclude_from_search
            'public'             => true,
            'has_archive'        => true,
            //Where to show the post type in the admin menu
            'show_ui'            => true,
            'query_var'          => false,
            // post preview button
            'publicly_queryable' => true,
            'map_meta_cap'       => true,
            'menu_position'      => 10,
            'supports'           => apply_filters('ydnPostTypeSupport', array('title')),
            'menu_icon'          => 'dashicons-download'
        );

        return $args;
    }

    public function getPostTypeLabels()
    {
        $labels = array(
            'name'               => _x(YDN_MENU_TITLE, 'post type general name', YDN_TEXT_DOMAIN),
            'singular_name'      => _x(YDN_MENU_TITLE, 'post type singular name', YDN_TEXT_DOMAIN),
            'menu_name'          => _x(YDN_MENU_TITLE, 'admin menu', YDN_TEXT_DOMAIN),
            'name_admin_bar'     => _x('Download', 'add new on admin bar', YDN_TEXT_DOMAIN),
            'add_new'            => _x('Add New', 'Download', YDN_TEXT_DOMAIN),
            'add_new_item'       => __('Add New Download', YDN_TEXT_DOMAIN),
            'new_item'           => __('New Download', YDN_TEXT_DOMAIN),
            'edit_item'          => __('Edit Download', YDN_TEXT_DOMAIN),
            'view_item'          => __('View Download', YDN_TEXT_DOMAIN),
            'all_items'          => __('All '.YDN_MENU_TITLE, YDN_TEXT_DOMAIN),
            'search_items'       => __('Search '.YDN_MENU_TITLE, YDN_TEXT_DOMAIN),
            'parent_item_colon'  => __('Parent '.YDN_MENU_TITLE.':', YDN_TEXT_DOMAIN),
            'not_found'          => __('No '.YDN_MENU_TITLE.' found.', YDN_TEXT_DOMAIN),
            'not_found_in_trash' => __('No '.YDN_MENU_TITLE.' found in Trash.', YDN_TEXT_DOMAIN)
        );

        return $labels;
    }

    public function addSubMenu() {
        add_submenu_page(
            'edit.php?post_type='.YDN_POST_TYPE,
            __('Downloader Type', YDN_TEXT_DOMAIN),
            __('Downloader Type', YDN_TEXT_DOMAIN),
            'read',
	        YDN_TYPES,
            array($this, 'typesPages')
        );
        
        add_submenu_page(
            'edit.php?post_type='.YDN_POST_TYPE,
            __('History', YDN_TEXT_DOMAIN),
            __('History', YDN_TEXT_DOMAIN),
            'read',
            YDN_HISTORY_PAGE,
            array($this, 'historyPage')
        );

        add_submenu_page(
            'edit.php?post_type='.YDN_POST_TYPE,
            __('Subscription', YDN_TEXT_DOMAIN),
            __('Subscription', YDN_TEXT_DOMAIN),
            'read',
	        YDN_SUBSCRIPTIONS_PAGE,
            array($this, 'subscription')
        );

        add_submenu_page(
            'edit.php?post_type='.YDN_POST_TYPE,
            __('Support', YDN_TEXT_DOMAIN),
            __('Support', YDN_TEXT_DOMAIN),
            'read',
            'ydn-support-page',
            array($this, 'supportPage')
        );
        
        add_submenu_page(
            'edit.php?post_type='.YDN_POST_TYPE,
            __('More Ideas?', YDN_TEXT_DOMAIN),
            __('More Ideas?', YDN_TEXT_DOMAIN),
            'read',
            'ydn-ideas-page',
            array($this, 'supportPage')
        );
        
        add_submenu_page(
            'edit.php?post_type='.YDN_POST_TYPE,
            __('More Plugins', YDN_TEXT_DOMAIN),
            __('More Plugins', YDN_TEXT_DOMAIN),
            'read',
	        YDN_MORE_PLUGINS_PAGE,
            array($this, 'morePlugins')
        );
    }

    public function subscription() {
	    require_once YDN_ADMIN_VIEWS_PATH.'subscription.php';
    }
    
    public function morePlugins() {
	    require_once YDN_ADMIN_VIEWS_PATH.'morePlugins.php';
    }

    public function typesPages() {
	    require_once YDN_ADMIN_VIEWS_PATH.'types.php';
    }
    
    public function supportPage() {

    }

    public function historyPage() {
        if (empty($_GET['currentProductId'])) {
            require_once YDN_VIEWS_PATH.'historyDownloads.php';
        }
        else {
            $typeObj = Downloader::findById($_GET['currentProductId']);
            require_once YDN_VIEWS_PATH.'downloadedProductDetail.php';
        }

    }

    public function registerTaxonomy()
    {
        $labels = array(
            'name'                       => _x('Categories', 'taxonomy general name', YDN_TEXT_DOMAIN),
            'singular_name'              => _x('Categories', 'taxonomy singular name', YDN_TEXT_DOMAIN),
            'search_items'               => __('Search Categories', YDN_TEXT_DOMAIN),
            'popular_items'              => __('Popular Categories', YDN_TEXT_DOMAIN),
            'all_items'                  => __('All Categories', YDN_TEXT_DOMAIN),
            'parent_item'                => null,
            'parent_item_colon'          => null,
            'edit_item'                  => __('Edit Category', YDN_TEXT_DOMAIN),
            'update_item'                => __('Update Category', YDN_TEXT_DOMAIN),
            'add_new_item'               => __('Add New Category', YDN_TEXT_DOMAIN),
            'new_item_name'              => __('New Category Name', YDN_TEXT_DOMAIN),
            'separate_items_with_commas' => __('Separate Categories with commas', YDN_TEXT_DOMAIN),
            'add_or_remove_items'        => __('Add or remove Categories', YDN_TEXT_DOMAIN),
            'choose_from_most_used'      => __('Choose from the most used Categories', YDN_TEXT_DOMAIN),
            'not_found'                  => __('No Categories found.', YDN_TEXT_DOMAIN),
            'menu_name'                  => __('Categories', YDN_TEXT_DOMAIN),
        );

        $args = array(
            'hierarchical'          => true,
            'labels'                => $labels,
            'show_ui'               => true,
            'show_admin_column'     => true,
            'sort'                  => 12,
            'update_count_callback' => '_update_post_term_count',
            'query_var'             => true,
            'capabilities' => array(
            )
        );

        register_taxonomy(YDN_CATEGORY_TAXONOMY, YDN_POST_TYPE, $args);
        register_taxonomy_for_object_type(YDN_CATEGORY_TAXONOMY, YDN_POST_TYPE);
    }
}