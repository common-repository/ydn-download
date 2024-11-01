<?php
use ydnDataTable\DownloadsHistory;
require_once YDN_DATA_TABLE_PATH.'DownloadsHistory.php';
?>

<div class="ydn-hidden-table-wrapper">
    <h2><?php _e('Downloads', YDN_TEXT_DOMAIN); ?></h2>
<?php
    $table = new DownloadsHistory();
    echo $table;
?>
</div>