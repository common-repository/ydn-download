<?php

namespace ydnDataTable;
require_once(dirname(__FILE__).'/ListTable.php');

class YDNTable extends YDNListTable
{
	protected $id = '';
	protected $columns = array();
	protected $displayColumns = array();
	protected $sortableColumns = array();
	protected $tablename = '';
	protected $rowsPerPage = 10;
	protected $initialOrder = array();

	public function __construct($id)
	{
		$this->id = $id;
		parent::__construct(array(
			'singular'=> 'wp_'.$id, //singular label
			'plural' => 'wp_'.$id.'s', //plural label
			'ajax' => false
		));
	}
	
	public function setId($id)
	{
		$this->id = $id;
	}
	
	public function setRowsPerPage($rowsPerPage)
	{
		$this->rowsPerPage = $rowsPerPage;
	}

	public function setColumns($columns)
	{
		$this->columns = $columns;
	}

	public function getColumns()
	{
		return $this->columns;
	}

	public function setDisplayColumns($displayColumns)
	{
		$this->displayColumns = $displayColumns;
	}

	public function setSortableColumns($sortableColumns)
	{
		$this->sortableColumns = $sortableColumns;
	}

	public function setTablename($tablename)
	{
		$this->tablename = $tablename;
	}

	public function getTablename()
	{
		return $this->tablename;
	}

	public function setInitialSort($orderableColumns)
	{
		$this->initialOrder = $orderableColumns;
	}

	public function get_columns()
	{
		return $this->displayColumns;
	}

	public function prepare_items()
	{
		global $wpdb;
		$table = $this->tablename;

		$query = 'SELECT '.implode(', ', $this->columns).' FROM '.$table;
		$this->customizeQuery($query);
		$totalItems = count($wpdb->get_results($query)); //return the total number of affected rows
		$perPage = $this->rowsPerPage;

		$totalPages = ceil($totalItems/$perPage);

		$orderby = isset($_GET['orderby']) ? sanitize_text_field($_GET['orderby']) : 'ASC';
		$order = isset($_GET['order']) ? sanitize_text_field($_GET['order']) : '';

		if (isset($this->initialOrder) && empty($order)) {
			foreach ($this->initialOrder as $key => $value) {
				$order = $value;
				$orderby = $key;
			}
		}

		if (!empty($orderby) && !empty($order)) {
			$query .= ' ORDER BY '.$orderby.' '.$order;
		}

		$paged = isset($_GET["paged"]) ? (int)$_GET["paged"] : '';

		if (empty($paged) || !is_numeric($paged) || $paged <= 0) {
			$paged = 1;
		}

		//adjust the query to take pagination into account
		if (!empty($paged) && !empty($perPage)) {
			$offset = ($paged - 1) * $perPage;
			$query .= ' LIMIT '.(int)$offset.','.(int)$perPage;
		}

		$this->set_pagination_args(array(
			"total_items" => $totalItems,
			"total_pages" => $totalPages,
			"per_page" => $perPage,
		));

		$columns = $this->get_columns();
		$hidden = array();
		$sortable = $this->get_sortable_columns();
		$this->_column_headers = array($columns, $hidden, $sortable);
		$items = $wpdb->get_results($query, ARRAY_N);
		/*Remove countdown data when its class does not exist.*/
		$this->customizeRowsData($items);

		$this->items = $items;
	}

	public function customizeRowsData(&$items) {

	}

	public function get_sortable_columns() {
		return $this->sortableColumns;
	}

	public function display_rows()
	{
		//get the records registered in the prepare_items method
		$records = $this->items;

		//get the columns registered in the get_columns and get_sortable_columns methods
		list($columns, $hidden) = $this->get_column_info();

		if (!empty($records)) {
			foreach($records as $rec) {
				echo '<tr>';

				$this->customizeRow($rec);
				for ($i = 0; $i<count($rec); $i++) {
					echo '<td>'.stripslashes($rec[$i]).'</td>';
				}

				echo '</tr>';
			}
		}
	}

	public function customizeRow(&$row)
	{

	}

	public function customizeQuery(&$query)
	{

	}

	public function __toString()
	{
		$this->prepare_items(); ?>
		<form method="get" id="posts-filter">
		<p class="search-box">
			 <input type="hidden" name="post_type" value="<?php echo YDN_POST_TYPE; ?>" >
			 <input type="hidden" name="page" value="subscribers" >
			 <?php $this->search_box('search', 'search_id'); ?>
		</p>
		<?php $this->display();?>
		</form>
		<?php
		return '';
	}

	// parent class method overriding
	public function extra_tablenav($which)
	{
	    $tableName = $this->getTablename();
		?>
        <div class="alignleft actions daterangeactions">
            <select name="ydn-bulk-action">
                <option value=""><?php _e('Bulk Actions', YDN_TEXT_DOMAIN) ?></option>
                <option value="delete"><?php _e('Delete', YDN_TEXT_DOMAIN) ?></option>
            </select>
            <input name="apply_action" id="post-query-submit" class="button ydn-datable-delete" data-table-name="<?php echo esc_attr($tableName); ?>" value="<?php _e('Apply', YDN_TEXT_DOMAIN)?>" type="submit">
        </div>
		<?php
	}
}
