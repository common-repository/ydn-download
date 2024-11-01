<?php
namespace ydn;

class FileDownloadPage {

	public static function renderTable($data) {
		ob_start();
		?>
			<table class="table table-striped">
	            <tbody>
	                <?php foreach ($data as $current) { ?>
	                	<tr class="">
		                    <td><?php echo $current['label']; ?></td>
		                    <th class="text-right"><?php echo $current['value']; ?></th>
		                </tr>
	                <?php } ?>
	            </tbody>
	        </table>
		<?php
		$cont = ob_get_contents();
		ob_end_clean();

		return $cont;
	}
}