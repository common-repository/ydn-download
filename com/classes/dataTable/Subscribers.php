<?php
namespace ydnDataTable;
use ydn\AdminHelper;

require_once dirname(__FILE__).'/Table.php';

class Subscribers extends YDNTable
{
	public function __construct()
	{
		global $wpdb;
		parent::__construct('');

		$this->setRowsPerPage(YDN_TABLE_LIMIT);
		$this->setTablename($wpdb->prefix.YDN_SUBSCRIBERS_TABLE_NAME);
		$this->setColumns(array(
			$this->tablename.'.id',
			'email',
			'cDate',
			$wpdb->prefix.YDN_POSTS_TABLE_NAME.'.post_title AS downloadType'
		));
		$this->setDisplayColumns(array(
			'bulk'=>'<input class="subs-bulk" type="checkbox" autocomplete="off">',
			'id' => 'ID',
			'email' => __('Email', YDN_TEXT_DOMAIN),
			'cDate' => __('Date', YDN_TEXT_DOMAIN),
			'downloadType' => __('Download Type', YDN_TEXT_DOMAIN)
		));
		$this->setSortableColumns(array(
			'id' => array('id', false),
			'email' => array('email', true),
			'cDate' => array('cDate', true),
			'downloadType' => array('downloadType', true),
			$this->setInitialSort(array(
				'id' => 'DESC'
			))
		));
	}

	public function customizeRow(&$row)
	{
		$title = $row[3];
		if (empty($title)) {
			$title = __('(no title)', YDN_TEXT_DOMAIN);
		}

		$row[4] = $title;
		$row[3] = AdminHelper::formattedDate($row[2]);
		$row[2] = $row[1];
		$row[1] = $row[0];
		$id = $row[0];
		$row[0] = '<input type="checkbox" name="ydn-delete-checkbox[]" value="'.esc_attr($id).'" class="ydn-delete-checkbox" data-delete-id="'.esc_attr($id).'">';
	}

	public function customizeQuery(&$query)
	{
		$query = AdminHelper::customizeSubsQuery($query);
	}
}