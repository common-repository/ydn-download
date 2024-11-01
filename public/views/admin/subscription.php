<?php
use ydnDataTable\Subscribers;
use ydn\Subscription;
use ydn\AdminHelper;

require_once YDN_DATA_TABLE_PATH.'Subscribers.php';
//$totalSubscribers = Subscription::getTotalSubscribers();
$totalSubscribers = array();
?>
	<div class="ydn-hidden-table-wrapper">
	<div class="headers-wrapper">
		<h2><?php _e('Subscribers', YDN_TEXT_DOMAIN)?>
			<?php if ($totalSubscribers): ?>
				<a href="javascript:void(0)" class="add-new-h2 ycd-export-subscriber"><?php _e('Export', YDN_TEXT_DOMAIN)?></a>
			<?php endif; ?>
		</h2>
	</div>
<?php
$table = new Subscribers();
echo $table;
?>
	</div>
