<?php
namespace ydn;

class ScriptsManager {

    public static function includeScripts() {
        $obj = new self();
        $hasShortcode = $obj->hasShortCodeOnThePage();

        if($hasShortcode) {
            $obj->generalScripts();
        }

        $isOurPost = $obj->isOurPost();

        if($isOurPost) {
            $obj->customScripts();
        }

    }

    public function generalScripts() {
        $jsFiles = array();
        $localizeData = array();

        $scriptData = array(
            'jsFiles' => apply_filters('ydnGeneralJsFiles', $jsFiles),
            'localizeData' => apply_filters('ydnGeneralJsLocalizedData', $localizeData)
        );

        $scriptData = apply_filters('ydnGeneralJs', array($scriptData));

        $this->loadScripts($scriptData);
    }

    public function customScripts() {
        $jsFiles = array();
        $localizeData = array();

        $id = get_queried_object_id();
        $obj = Downloader::findById($id);
        $obj->includeScripts();

        $jsFiles[] = array('folderUrl'=> YDN_FRONT_JS_URL, 'filename' => 'Downloader.js');

        $scriptData = array(
            'jsFiles' => apply_filters('ydnCustomJsFiles', $jsFiles),
            'localizeData' => apply_filters('ydnCustomJsLocalizedData', $localizeData)
        );

        $scriptData = apply_filters('ydnCustomJs', array($scriptData));
        $this->loadScripts($scriptData);
    }

    public function loadScripts($scripts) {

        foreach($scripts as $script) {

            if(empty($script['jsFiles'])) {
                continue;
            }

            foreach($script['jsFiles'] as $jsFile) {

                if(empty($jsFile['folderUrl'])) {
                    wp_enqueue_script($jsFile['filename']);
                    continue;
                }

                $dirUrl = $jsFile['folderUrl'];
                $dep = (!empty($jsFile['dep'])) ? $jsFile['dep'] : '';
                $ver = (!empty($jsFile['ver'])) ? $jsFile['ver'] : '';
                $inFooter = (!empty($jsFile['inFooter'])) ? $jsFile['inFooter'] : '';

                ScriptsIncluder::registerScript($jsFile['filename'], array(
                        'dirUrl'=> $dirUrl,
                        'dep' => $dep,
                        'ver' => $ver,
                        'inFooter' => $inFooter
                    )
                );
                ScriptsIncluder::enqueueScript($jsFile['filename']);
            }

            if(empty($script['localizeData'])) {
                continue;
            }

            $localizeDatas = $script['localizeData'];

            foreach($localizeDatas  as $localizeData) {
                ScriptsIncluder::localizeScript($localizeData['handle'], $localizeData['name'], $localizeData['data']);
            }
        }
    }


    public function isOurPost() {
        $currentPost = $this->getCurrentPost();

        if(empty($currentPost) || !is_object($currentPost)) {
            return false;
        }

        return ($currentPost->post_type == YDN_POST_TYPE);
    }

    public function hasShortCodeOnThePage() {
        $currentPost = $this->getCurrentPost();
        $content = $currentPost->post_content;

        return has_shortcode($content, 'ydn_downloader');
    }

    private function getCurrentPost() {
        $id = get_queried_object_id();
        $currentPost = get_post($id);

        return $currentPost;
    }
}