<?php
namespace ydn;

class Tickbox {
    private $isEditorButton = false;
    private $isLoadedMediaData = false;

    public function __construct($isEditorButton = false, $isLoadedMediaData = false) {
        if (isset($isEditorButton)) {
            $this->isEditorButton = $isEditorButton;
        }
        if (isset($isLoadedMediaData)) {
            $this->isLoadedMediaData = $isLoadedMediaData;
        }
        $this->mediaButton();
        if(!$this->isLoadedMediaData) {
            add_action( 'admin_footer', array($this, 'ydnAdminTickBox'));
        }
    }

    private function mediaButton() {
        global $pagenow, $typenow;
        $output = '';

        /** Only run in post/page creation and edit screens */
        if (in_array($pagenow, array('post.php', 'page.php', 'post-new.php', 'post-edit.php')) && $typenow != 'download' ) {
            wp_enqueue_script('jquery-ui-dialog');
            wp_register_style('ydn_jQuery_ui_css', YDN_ADMIN_CSS_URL.'jQueryDialog/ydn-jquery-ui.css');
            wp_enqueue_style('ydn_jQuery_ui_css');
            $img = '<span class="wp-media-buttons-icon dashicons dashicons-download" id="ydn-media-button" style="margin-right: 5px !important;"></span>';
            $output = '<a href="javascript:void(0);" class="button ydn-thickbox" style="padding-left: .4em;">'.$img.__('Downloader', YDN_TEXT_DOMAIN).'</a>';
        }

        if (!$this->isEditorButton) {
            echo $output;
        }
    }


    function ydnAdminTickBox() {
        global $pagenow, $typenow;

        // Only run in post/page creation and edit screens
        if ( in_array( $pagenow, array( 'post.php', 'page.php', 'post-new.php', 'post-edit.php' ) ) && $typenow != 'download' ) { ?>
            <script type="text/javascript">
                function insertYdnDownload() {
                    var id = jQuery('.ydn-downloader').val();

                    // Return early if no download is selected
                    if (!id) {
                        alert('<?php _e('Select your downloader', YDN_TEXT_DOMAIN); ?>');
                        return;
                    }

                    function getTextTabSelectionText() // javascript
                    {
                        // obtain the object reference for the <textarea>
                        var txtarea = document.querySelector("textarea[name='content']");
                        // obtain the index of the first selected character
                        var start = txtarea.selectionStart;
                        // obtain the index of the last selected character
                        var finish = txtarea.selectionEnd;
                        // obtain the selected text
                        var sel = txtarea.value.substring(start, finish);
                        // do something with the selected content

                        return sel;
                    }

                    if (tinyMCE.activeEditor == null) {
                        var selection = getTextTabSelectionText();
                    }
                    else {
                        var selection = tinyMCE.activeEditor.selection.getContent();
                    }

                    // Send the shortcode to the editor
                    window.send_to_editor('[ydn_downloader id="'+id+'"]');
                    jQuery('#ydn-dialog').dialog('close')
                }
                jQuery(document).ready(function ($) {
                    $('.ydn-thickbox').bind('click', function() {
                        jQuery('#ydn-dialog').dialog({
                            width: 450,
                            modal: true,
                            title: "Insert the shortcode",
                            dialogClass: "ydn-bootstrap-wrapper"
                        });
                    });
                });
            </script>
            <?php
                $downloadsObj = Downloader::getDownloadersObj();
                $idTitle = Downloader::shapeIdTitleData($downloadsObj);
            ?>

            <div id="ydn-dialog" style="display: none;">
                <div class="wrap" style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;">
                    <p>
                        <label><?php _e('Select Downloader', YDN_TEXT_DOMAIN); ?>:</label>
                        <?php if(!empty($idTitle)): ?>
                            <?php  echo AdminHelper::createSelectBox($idTitle, '', array('name' => 'ydn-option', 'class' => 'ydn-downloader')); ?>
                        <?php else: ?>
                            <a href="<?php echo YDN_ADMIN_URL.'edit.php?post_type='.YDN_POST_TYPE; ?>"><?php _e('Add New Downloader', YDN_TEXT_DOMAIN); ?></a>
                        <?php endif; ?>
                    </p>
                    <p class="submit">
                        <input type="button" id="ydn-insert-download" class="button-primary" value="<?php _e('Insert', YDN_TEXT_DOMAIN)?>" onclick="insertYdnDownload();" />
                        <a id="ydn-cancel-download-insert" class="button-secondary" onclick="jQuery('#ydn-dialog').dialog('close');"><?php _e( 'Cancel', 'easy-digital-downloads' ); ?></a>
                    </p>
                </div>
            </div>
            <?php
        }
    }
}