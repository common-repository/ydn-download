<?php
namespace ydn;

class Installer {

    public static function createTables($tables, $blogId = '') {
        global $wpdb;
        if(empty($tables)) {
            return false;
        }

        foreach ($tables as $table) {
            $createTable = 'CREATE TABLE IF NOT EXISTS ';
            $createTable .= $wpdb->prefix.$blogId;
            $createTable .= $table;

            $wpdb->query($createTable);
        }

        return true;
    }

    public static function getAllNeededTables() {
        $tablesSql = array();
        $dbEngine = self::getDatabaseEngine();

        $tablesSql[] = YDN_DOWNLOADS_HISTORY.' (
                `id` INT(11) NOT NULL AUTO_INCREMENT ,
                `product_id` INT(11) NOT NULL ,
                `file_id` INT(11) NOT NULL ,
                `date` DATE NOT NULL ,
                `ip` VARCHAR(255) NOT NULL ,
                `file_label` VARCHAR(255) NOT NULL ,
                `version` VARCHAR(255) NOT NULL ,
                `options` TEXT NOT NULL ,
                PRIMARY KEY (`id`)
            ) ENGINE = '.$dbEngine.' CHARSET=utf8 COLLATE utf8_general_ci;';
	
	    $tablesSql[] = YDN_SUBSCRIBERS_TABLE_NAME.' (
					`id` int(12) NOT NULL AUTO_INCREMENT,
					`firstName` varchar(255),
					`lastName` varchar(255),
					`email` varchar(255),
					`subscriptionType` int(12),
					`cDate` date,
					`status` varchar(255),
					`unsubscribed` int(11) default 0,
					PRIMARY KEY (id)
			) ENGINE='.$dbEngine.' DEFAULT CHARSET=utf8;';

        return $tablesSql;
    }

    public static function install() {
        $tables = self::getAllNeededTables();
        YdnShowReviewNotice::setInitialDates();

        $filteredTables = apply_filters('ydnTablesInstall', $tables);

        self::createTables($filteredTables);


        // get_current_blog_id() == 1 When plugin activated inside the child of multisite instance
        if (is_multisite() && get_current_blog_id() == 1) {
            global $wp_version;

            if ($wp_version > '4.6.0') {
                $sites = get_sites();
            }
            else {
                $sites = wp_get_sites();
            }

            foreach ($sites as $site) {

                if ($wp_version > '4.6.0') {
                    $blogId = $site->blog_id.'_';
                }
                else {
                    $blogId = $site['blog_id'].'_';
                }
                // blog Id 1 for multisite main site
                if ($blogId != 1) {
                    self::createTables($filteredTables, $blogId);
                }
            }
        }
    }

    public static function getDatabaseEngine()
    {
        global $wpdb;
        $dbName = $wpdb->dbname;
        $engine = 'InnoDB';
        $engineCheckSql = "SELECT ENGINE FROM information_schema.TABLES WHERE TABLE_SCHEMA = '$dbName'";
        $result = $wpdb->get_results($engineCheckSql, ARRAY_A);
        if (!empty($result)) {
            $engineCheckSql = "SHOW TABLE STATUS WHERE Name = '".$wpdb->prefix."users' AND Engine = 'MyISAM'";
            $result = $wpdb->get_results($engineCheckSql, ARRAY_A);
            if (isset($result[0]['Engine']) && $result[0]['Engine'] == 'MyISAM') {
                $engine = 'MyISAM';
            }
        }

        return $engine;
    }
}