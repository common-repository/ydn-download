<?php
use ydn\DownloadManager;
$deailsObj = DownloadManager::findById($_GET['currentProductId']);
$options = $deailsObj->getOptions();
?>
<div class="ydn-bootstrap-wrapper">
    <div class="row ydn-download-details-wrapper">
        <div class="col-md-8">
            <div class="panel panel-default ui-sortable-handle">
                <div class="panel-heading"><?php _e('Details', YDN_TEXT_DOMAIN); ?></div>
                <div class="panel-body">
                    <div class="row">
                        <label class="col-md-6">file_label</label>
                        <div class="col-md-6">
                            <?php echo $options['file_label'];?>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-md-6">Version</label>
                        <div class="col-md-6">
                            <?php  echo $options['version'];?>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-md-6">IP</label>
                        <div class="col-md-6">
                            <?php  echo $options['ip'];?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>