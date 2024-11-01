<?php
use ydn\Downloader;
use ydn\AdminHelper;

// Creating the widget
class ydn_widget extends WP_Widget {

    function __construct() {
        parent::__construct(
// Base ID of your widget
	        YDN_WIDGET,
// Widget name will appear in UI
            __('Downloader', YDN_TEXT_DOMAIN),
// Widget description
            array('description' => __('Downloader widget', YDN_TEXT_DOMAIN),)
        );
    }

// Creating widget front-end
    public function widget($args, $instance) {
        $cdId = (int)@$instance['ydnOption'];

        echo do_shortcode('[ydn_downloader id='.$cdId.']');
    }

// Widget Backend
    public function form($instance) {
        $popups = Downloader::getDownloadersObj();
        $idTitle = Downloader::shapeIdTitleData($popups);
        // Widget admin form
        $optionSaved = @$this->get_field_name('ydnOption');
        $optionName = @$instance['ydnOption'];
        ?>
        <p>
            <label><?php _e('Select download item', YDN_TEXT_DOMAIN); ?>:</label>
            <?php echo AdminHelper::createSelectBox($idTitle, $optionName, array('name' => $optionSaved)); ?>
        </p>
        <?php
    }

// Updating widget replacing old instances with new
    public function update($new_instance, $old_instance = array()) {

        $instance = array();

        $instance['ydnOption'] = $new_instance['ydnOption'];
        return $instance;
    }
} // Class wpb_widget ends here
