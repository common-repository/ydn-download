<?php
namespace ydnDataTable;
use W3TC\Varnish_Flush;
use ydn\AdminHelper;

require_once dirname(__FILE__).'/Table.php';

class DownloadsHistory extends YDNTable
{
	public function __construct()
	{
		global $wpdb;
		parent::__construct('');

		$this->setRowsPerPage(YDN_TABLE_LIMIT);
		$this->setTablename($wpdb->prefix.YDN_DOWNLOADS_HISTORY);
		$this->setColumns(array(
			$this->tablename.'.id',
			'date',
            $this->tablename.'.product_id'
		));
		$this->setDisplayColumns(array(
			'bulk'=>'<input class="subs-bulk" type="checkbox" autocomplete="off">',
			'product_id' => 'ID',
            'title' => __('Download Item', YDN_TEXT_DOMAIN),
			'date' => __('Date', YDN_TEXT_DOMAIN)
		));
		$this->setSortableColumns(array(
			'product_id' => array('id', false),
			'date' => array('date', true),
			'countdownType' => array('countdownType', true),
			$this->setInitialSort(array(
				'id' => 'DESC'
			))
		));
	}

	public function customizeRow(&$row)
	{
		$title = get_the_title($row[2]);
		if (empty($title)) {
			$title = __('(no title)', YDN_TEXT_DOMAIN);
		}
        $currentLink = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
        $currentLink = add_query_arg( array(
            'currentProductId' => $row[0]
        ), $currentLink);

		$row[2] = '<a href="'.esc_attr($currentLink).'">'.$title.'</a>';
		$row[3] = AdminHelper::getFormattedDate($row[1]);
		$row[1] = $row[0];
		$id = $row[0];
		$row[0] = '<input type="checkbox" name="ydn-delete-checkbox[]" value="'.esc_attr($id).'" class="ydn-delete-checkbox" data-delete-id="'.esc_attr($id).'">';
	}

	public function customizeQuery(&$query)
	{
		$query = AdminHelper::filterHistory($query);
	}
}
