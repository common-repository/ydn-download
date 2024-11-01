<?php
namespace ydn;
global $YDN_TYPES;
$types = Downloader::getDownloadTypesObj();
?>
<div class="ydn-bootstrap-wrapper">
<div class="row">
	<div class="col-xs-6">
		<h3><?php _e('Choose Download Type', YDN_TEXT_DOMAIN); ?></h3>
	</div>
</div>
<div class="ydn-add-new-hr"></div>
<div class="ydn-wrapper">
	<?php foreach ($types as $typeObj): ?>
		<?php $type = $typeObj->getName(); ?>
		<?php
		$isAvaliable = $typeObj->isAvailable();
		if (!$isAvaliable) {
			continue;
		}
		?>
		<a class="create-scroll-link ydn-type-div ydn-<?php echo $type; ?>-div" href="<?php echo AdminHelper::buildCreateDownloddUrl($typeObj); ?>">
			<div class="ydn-scroll-type-wrapper">
				<div class="ydn-type-icon ydn-type-div ydn-<?php echo $type; ?>-type"></div>
				<div class="ydn-type-view-footer">
					<span class="ydn-icon-title"><?php echo $YDN_TYPES['titles'][$type]; ?></span>
				</div>
			</div>
		</a>
	<?php endforeach; ?>
</div>
</div>