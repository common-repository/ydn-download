<?php
namespace ydn;
use \DateTime;

class YdnShowReviewNotice {

    public function __toString() {
        $content = '';
        $allowToShow = $this->allowToShowUsageDays();

        if(!$allowToShow) {
            return $content;
        }

        $content = $this->getReviewContent('usageDayes');

        return $content;
    }

    private function allowToShowUsageDays() {
        $shouldOpen = true;
        $dontShowAgain = get_option('YdnDontShowReviewNotice');

        if($dontShowAgain) {
            return !$shouldOpen;
        }
        $periodNextTime = get_option('YdnShowNextTime');

        // When period next time does not exits it means the user is old
        if(!$periodNextTime) {
            YdnShowReviewNotice::setInitialDates();

            return !$shouldOpen;
        }
        $currentData = new DateTime('now');
        $timeNow = $currentData->format('Y-m-d H:i:s');
        $timeNow = strtotime($timeNow);

        return $periodNextTime < $timeNow;
    }

    private function getReviewContent($type) {
        $content = $this->getMaxOpenDaysMessage($type);
        ob_start();
        ?>
        <div id="welcome-panel" class="welcome-panel ydn-review-block">
            <div class="welcome-panel-content">
                <?php echo $content; ?>
            </div>
        </div>
        <?php
        $reviewContent = ob_get_contents();
        ob_end_clean();

        return $reviewContent;
    }

    private function getMainTableCreationDate() {
        global $wpdb;

        $query = $wpdb->prepare('SELECT table_name, create_time FROM information_schema.tables WHERE table_schema="%s" AND  table_name="%s"', DB_NAME, $wpdb->prefix.'expm_maker');
        $results = $wpdb->get_results($query, ARRAY_A);

        if(empty($results)) {
            return 0;
        }

        $createTime = $results[0]['create_time'];
        $createTime = strtotime($createTime);
        update_option('YdnInstallDate', $createTime);
        $diff = time()-$createTime;
        $days  = floor($diff/(60*60*24));

        return $days;
    }

    private function getPopupUsageDays() {
        $installDate = get_option('YdnInstallDate');

        $timeDate = new DateTime('now');
        $timeNow = strtotime($timeDate->format('Y-m-d H:i:s'));

        $diff = $timeNow-$installDate;

        $days  = floor($diff/(60*60*24));

        return $days;
    }

    private  function getMaxOpenDaysMessage($type) {
        $getUsageDays = $this->getPopupUsageDays();
        $firstHeader = '<h1 class="ydn-review-h1"><strong class="ydn-review-strong">Wow!</strong> You’ve been using Downloader on your site for '.$getUsageDays.' days</h1>';
        $popupContent = $this->getMaxOepnContent($firstHeader, $type);

        $popupContent .= $this->showReviewBlockJs();

        return $popupContent;
    }

    private function getMaxOepnContent($firstHeader, $type) {
        $ajaxNonce = wp_create_nonce('ydnReviewNotice');

        ob_start();
        ?>
        <style>
            .ydn-buttons-wrapper .press{
                box-sizing:border-box;
                cursor:pointer;
                display:inline-block;
                font-size:1em;
                margin:0;
                padding:0.5em 0.75em;
                text-decoration:none;
                transition:background 0.15s linear
            }
            .ydn-buttons-wrapper .press-grey {
                background-color:#9E9E9E;
                border:2px solid #9E9E9E;
                color: #FFF;
            }
            .ydn-buttons-wrapper .press-lightblue {
                background-color:#03A9F4;
                border:2px solid #03A9F4;
                color: #FFF;
            }
            .ydn-review-wrapper{
                text-align: center;
                padding: 20px;
            }
            .ydn-review-wrapper p {
                color: black;
            }
            .ydn-review-h1 {
                font-size: 22px;
                font-weight: normal;
                line-height: 1.384;
            }
            .ydn-review-h2{
                font-size: 20px;
                font-weight: normal;
            }
            :root {
                --main-bg-color: #1ac6ff;
            }
            .ydn-review-strong{
                color: var(--main-bg-color);
            }
            .ydn-review-mt20{
                margin-top: 20px
            }
        </style>
        <div class="ydn-review-wrapper">
            <div class="ydn-review-description">
                <?php echo $firstHeader; ?>
                <h2 class="ydn-review-h2">This is really great for your website score.</h2>
                <p class="ydn-review-mt20">Have your input in the development of our plugin, and we’ll provide better conversions for your site!<br /> Leave your 5-star positive review and help us go further to the perfection!</p>
            </div>
            <div class="ydn-buttons-wrapper">
                <button class="press press-grey ydn-button-1 ydn-already-did-review" data-ajaxnonce="<?php echo esc_attr($ajaxNonce); ?>">I already did</button>
                <button class="press press-lightblue ydn-button-3 ydn-already-did-review" data-ajaxnonce="<?php echo esc_attr($ajaxNonce); ?>" onclick="window.open('<?php echo YDN_DOWNLOAD_REVIEW_URL; ?>')">You worth it!</button>
                <button class="press press-grey ydn-button-2 ydn-show-popup-period" data-ajaxnonce="<?php echo esc_attr($ajaxNonce); ?>" data-message-type="<?php echo $type; ?>">Maybe later</button>
            </div>
        </div>
        <?php
        $content = ob_get_contents();
        ob_end_clean();

        return $content;
    }

    private function showReviewBlockJs() {
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery('.ydn-already-did-review').each(function () {
                jQuery(this).on('click', function () {
                    var ajaxNonce = jQuery(this).attr('data-ajaxnonce');

                    var data = {
                        action: 'ydn_dont_show_review_notice',
                        ajaxNonce: ajaxNonce
                    };
                    jQuery.post(ajaxurl, data, function(response,d) {
                        if(jQuery('.ydn-review-block').length) {
                            jQuery('.ydn-review-block').remove();
                        }
                    });
                });
            });

            jQuery('.ydn-show-popup-period').on('click', function () {
                var ajaxNonce = jQuery(this).attr('data-ajaxnonce');
                var messageType = jQuery(this).attr('data-message-type');

                var data = {
                    action: 'ydn_change_review_show_period',
                    messageType: messageType,
                    ajaxNonce: ajaxNonce
                };
                jQuery.post(ajaxurl, data, function(response,d) {
                    if(jQuery('.ydn-review-block').length) {
                        jQuery('.ydn-review-block').remove();
                    }
                });
            });
        </script>
        <?php
        $script = ob_get_contents();
        ob_end_clean();

        return $script;
    }

    public static function setInitialDates() {
        $usageDays = get_option('YdnUsageDays');
        if(!$usageDays) {
            update_option('YdnUsageDays', 0);

            $timeDate = new DateTime('now');
            $installTime = strtotime($timeDate->format('Y-m-d H:i:s'));
            update_option('YdnInstallDate', $installTime);
            $timeDate->modify('+'.YDN_SHOW_REVIEW_PERIOD.' day');

            $timeNow = strtotime($timeDate->format('Y-m-d H:i:s'));
            update_option('YdnShowNextTime', $timeNow);
        }
    }

    public static function deleteInitialDates() {
        delete_option('YdnUsageDays');
        delete_option('YdnInstallDate');
        delete_option('YdnShowNextTime');
    }
}