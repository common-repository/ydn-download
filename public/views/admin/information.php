<div class="ydn-bootstrap-wrapper">
    <?php if(empty($_GET['post'])): ?>
        <div class="row1">
            <div class="">
                <label for="ydn-info-shortcode"><?php _e('Shortcode', YDN_TEXT_DOMAIN)?></label>
                <p><?php _e('No download information for new downloads.', YDN_TEXT_DOMAIN); ?></p>
            </div>
        </div>
    <?php else: ?>
        <div class="row1">
            <div>
                <label for="ydn-info-shortcode"><?php _e('Shortcode', YDN_TEXT_DOMAIN)?></label>
               <p><div class="ydn-tooltip">
                    <span class="ydn-tooltiptext" id="ydn-tooltip-<?php echo $_GET['post']; ?>"><?php _e('Copy to clipboard', YDN_TEXT_DOMAIN)?></span>
                    <input type="text" class="download-shortcode" id="ydn-shortcode-input-<?php echo $_GET['post']; ?>" data-id="<?php echo $_GET['post']; ?>" value="[ydn_downloader id='<?php echo $_GET['post']; ?>']" readonly="" onfocus="this.select()">
                </div></p>
            </div>
        </div>
    <?php endif;?>
    <label>
		<?php _e('Current version'); ?>
    </label>
    <p class="current-version-text" style="color: #3474ff;"><?php echo YDN_VERSION_TEXT; ?></p>
    <label>
		<?php _e('Last update date'); ?>
    </label>
    <p style="color: #11ca79;"><?php echo YDN_LAST_UPDATE_DATE; ?></p>
    <label>
		<?php _e('Next update date'); ?>
    </label>
    <p style="color: #efc150;"><?php echo YDN_NEXT_UPDATE_DATE; ?></p>
    <input type="hidden" name="ydn-type" value="link">
</div>
<?php
$type = $this->getType();
?>
<input type="hidden" name="ydn-type" value="<?php echo esc_attr($type); ?>">