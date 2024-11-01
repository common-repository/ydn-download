function WpDownloaderBlock() {

}

WpDownloaderBlock.prototype.init = function() {
    if (typeof wp == 'undefined' || typeof wp.element == 'undefined' || typeof wp.blocks == 'undefined' || typeof wp.editor == 'undefined' || typeof wp.components == 'undefined') {
        return false;
    }
    var localizedParams = YDN_GUTENBERG_PARAMS;

    var __ = wp.i18n;
    var createElement     = wp.element.createElement;
    var registerBlockType = wp.blocks.registerBlockType;
    var InspectorControls = wp.editor.InspectorControls;
    var _wp$components    = wp.components,
        SelectControl     = _wp$components.SelectControl,
        TextareaControl   = _wp$components.TextareaControl,
        ToggleControl     = _wp$components.ToggleControl,
        PanelBody         = _wp$components.PanelBody,
        ServerSideRender  = _wp$components.ServerSideRender,
        Placeholder       = _wp$components.Placeholder;

    registerBlockType('ydndownload/downloader', {
        title: localizedParams.title,
        description: localizedParams.description,
        keywords: ['download', 'downloader manager', 'download button'],
        category: 'widgets',
        icon: 'download',
        attributes: {
            downloaderId: {
                type: 'number'
            }
        },
        edit(props) {
            const {
                attributes: {
                    downloaderId = '',
                    displayTitle = false,
                    displayDesc = false
                },
                setAttributes
            } = props;

            const downloaderOptions = [];
            let allDownloads = YDN_GUTENBERG_PARAMS.allDownloads;
            for(var id in allDownloads) {
                var currentdownObj = {
                    value: id,
                    label: allDownloads[id]
                }
                downloaderOptions.push(currentdownObj);
            }
            downloaderOptions.unshift({
                value: '',
                label: YDN_GUTENBERG_PARAMS.downloader_select
            })
            let jsx;

            function selectDownloade(value) {
                setAttributes({
                    downloaderId: value
                });
            }

            function setContent(value) {
                setAttributes({
                    content: value
                });
            }

            function toggleDisplayTitle(value) {
                setAttributes({
                    displayTitle: value
                });
            }

            function toggleDisplayDesc(value) {
                setAttributes({
                    displayDesc: value
                });
            }

            jsx = [
                <InspectorControls key="downloader-gutenberg-form-selector-inspector-controls">
                <PanelBody title={'downloader title'}>
                <SelectControl
            label = {''}
            value = {downloaderId}
            options = {downloaderOptions}
            onChange = {selectDownloade}
                />
                <ToggleControl
            label = {YDN_GUTENBERG_PARAMS.i18n.show_title}
            checked = {displayTitle}
            onChange = {toggleDisplayTitle}
                />
                <ToggleControl
            label = {YDN_GUTENBERG_PARAMS.i18n.show_description}
            checked = {displayDesc}
            onChange = {toggleDisplayDesc}
                />
                </PanelBody>
                </InspectorControls>
        ];

            if (downloaderId) {
                return '[ydn_downloader id="'+downloaderId+'"]';
            }
            else {
                jsx.push(
                <Placeholder
                key="ydn-gutenberg-form-selector-wrap"
                className="ydn-gutenberg-form-selector-wrapper">
                    <SelectControl
                key = "ydn-gutenberg-form-selector-select-control"
                value = {downloaderId}
                options = {downloaderOptions}
                onChange = {selectDownloade}
                    />
                    <SelectControl
                key = "ydn-gutenberg-form-selector-select-control"
                onChange = {selectDownloade}
                    />
                    </Placeholder>
            );
            }

            return jsx;
        },
        save(props) {

            return '[ydn_downloader id="'+props.attributes.downloaderId+'"]';
        }
    });
};

jQuery(document).ready(function () {
    var block = new WpDownloaderBlock();
    block.init();
});