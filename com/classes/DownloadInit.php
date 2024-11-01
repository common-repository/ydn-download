<?php
namespace ydn;

class DownloadInit {

    private static $instance = null;

	public function __construct() {
		$this->init();
	}

    public static function getInstance() {
        if(!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

	public function init() {
	    $this->includeFiles();
		$this->hooks();
		$this->initAction();
	}

	public function initAction() {
	    new Actions();
    }

	public function hooks() {
        register_activation_hook(YDN_FILE_NAME, array($this, 'activate'));
        register_deactivation_hook(YDN_FILE_NAME, array($this, 'deactivate'));
		add_action('admin_init', array($this, 'pluginRedirect'));
    }

    public function activate()
    {
        Installer::install();
    }

	private function includeFiles() {
		if(YDN_PKG_VERSION > YDN_FREE_VERSION) {
			require_once(YDN_HELPERS_PATH.'ConditionsConfigPro.php');
		}
		require_once(YDN_CLASS_PATH.'DownloadType.php');
	    require_once(YDN_HELPERS_PATH.'MultipleChoiceButton.php');
	    require_once(YDN_HELPERS_PATH.'FormRenderHelper.php');
	    require_once(YDN_HELPERS_PATH.'AdminHelper.php');
		if(YDN_PKG_VERSION > YDN_FREE_VERSION) {
			require_once YDN_CLASS_ADMIN_PATH.'ConditionsConditionBuilder.php';
		}
	    require_once(YDN_CLASS_ADMIN_PATH.'ShowReviewNotice.php');
	    require_once(YDN_CLASS_ADMIN_PATH.'Installer.php');
	    require_once(YDN_CLASS_ADMIN_PATH.'YdnWidget.php');
	    require_once(YDN_CLASS_PATH.'FileDownloadPage.php');
	    require_once(YDN_TYPES_CLASS_PATH.'Downloader.php');
        require_once(YDN_CLASS_PATH.'DownloaderModel.php');
        require_once(YDN_TYPES_CLASS_PATH.'LinkDownloader.php');
	    require_once(YDN_HELPERS_PATH.'AdminHelper.php');
	    require_once(YDN_HELPERS_PATH.'ScriptsIncluder.php');
	    require_once(YDN_CLASS_PATH.'Js.php');
	    require_once(YDN_FRONT_CLASS_PATH.'ScriptsManager.php');
	    require_once(YDN_CLASS_PATH.'Css.php');
	    require_once(YDN_CLASS_PATH.'DownloadManager.php');
	    require_once(YDN_CLASS_PATH.'Ajax.php');
	    require_once(YDN_CLASS_ADMIN_PATH.'Tickbox.php');
	    require_once(YDN_CLASS_PATH.'Checker.php');
	    require_once(YDN_CLASS_PATH.'Actions.php');
	    require_once(YDN_CLASS_PATH.'Filters.php');
	    require_once(YDN_CLASS_PATH.'RegisterPostType.php');
    }
	
	public function pluginRedirect() {
		if (!get_option('ydn_redirect')) {
			update_option('ydn_redirect', 1);
			exit(wp_redirect(admin_url('edit.php?post_type='.YDN_POST_TYPE)));
		}
	}
	
	public function deactivate() {
		delete_option('ydn_redirect');
	}
}

DownloadInit::getInstance();