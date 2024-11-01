<?php
namespace ydn;

class LinkDownloader extends Downloader {
    public function metaboxes() {
        parent::metaboxes();
        add_action('ydnAdditionalMetaboxes', array($this, 'additionalMetaboxes'), 100, 1);
    }

    public function additionalMetaboxes($metaboxes) {
        $metaboxes['ydnOtherConditionsMetaBoxView'] = array(
            'key' => 'ydnOtherConditionsMetaBoxView',
            'displayName' => 'Main Settings',
            'filePath' => YDN_VIEWS_PATH.'main.php',
            'priority' => 'high'
        );

        return $metaboxes;
    }

    private function downloadPage() {
        $title = $this->getOptionValue('ydn-file-label');
        $src = $this->getOptionValue('ydn-target-link');

        return '<a href="'.esc_attr($src).'" download class="ydn-download-link">'.$title.'</a>';
    }

    private function shortcodePage() {
        $id = $this->getId();
        $linkLabel = $this->getOptionValue('ydn-link-label');

        return '<a href="'.esc_attr(get_permalink($id)).'" class="ydn-download-link">'.$linkLabel.'</a>';
    }

    public function getViewContent() {
        $currentId = get_queried_object_id();
        $id = $this->getId();

        if ($currentId != $id) {
            // shorcode page
            return $this->shortcodePage();
        }
        else {
            return $this->downloadPage();
        }
    }
}