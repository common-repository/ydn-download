"use strict";

function WpDownloaderBlock() {}

WpDownloaderBlock.prototype.init = function () {
    if (typeof wp == 'undefined' || typeof wp.element == 'undefined' || typeof wp.blocks == 'undefined' || typeof wp.editor == 'undefined' || typeof wp.components == 'undefined') {
        return false;
    }

    var localizedParams = YDN_GUTENBERG_PARAMS;
    var __ = wp.i18n;
    var createElement = wp.element.createElement;
    var registerBlockType = wp.blocks.registerBlockType;
    var InspectorControls = wp.editor.InspectorControls;
    var _wp$components = wp.components,
        SelectControl = _wp$components.SelectControl,
        TextareaControl = _wp$components.TextareaControl,
        ToggleControl = _wp$components.ToggleControl,
        PanelBody = _wp$components.PanelBody,
        ServerSideRender = _wp$components.ServerSideRender,
        Placeholder = _wp$components.Placeholder;
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
        edit: function edit(props) {
            var _props$attributes = props.attributes,
                _props$attributes$dow = _props$attributes.downloaderId,
                downloaderId = _props$attributes$dow === void 0 ? '' : _props$attributes$dow,
                _props$attributes$dis = _props$attributes.displayTitle,
                displayTitle = _props$attributes$dis === void 0 ? false : _props$attributes$dis,
                _props$attributes$dis2 = _props$attributes.displayDesc,
                displayDesc = _props$attributes$dis2 === void 0 ? false : _props$attributes$dis2,
                setAttributes = props.setAttributes;
            var downloaderOptions = [];
            var allDownloads = YDN_GUTENBERG_PARAMS.allDownloads;

            for (var id in allDownloads) {
                var currentdownObj = {
                    value: id,
                    label: allDownloads[id]
                };
                downloaderOptions.push(currentdownObj);
            }

            downloaderOptions.unshift({
                value: '',
                label: YDN_GUTENBERG_PARAMS.downloader_select
            });
            var jsx;

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

            jsx = [React.createElement(InspectorControls, {
                key: "downloader-gutenberg-form-selector-inspector-controls"
            }, React.createElement(PanelBody, {
                title: 'downloader title'
            }, React.createElement(SelectControl, {
                label: '',
                value: downloaderId,
                options: downloaderOptions,
                onChange: selectDownloade
            }), React.createElement(ToggleControl, {
                label: YDN_GUTENBERG_PARAMS.i18n.show_title,
                checked: displayTitle,
                onChange: toggleDisplayTitle
            }), React.createElement(ToggleControl, {
                label: YDN_GUTENBERG_PARAMS.i18n.show_description,
                checked: displayDesc,
                onChange: toggleDisplayDesc
            })))];

            if (downloaderId) {
                return '[ydn_downloader id="' + downloaderId + '"]';
            } else {
                jsx.push(React.createElement(Placeholder, {
                    key: "ydn-gutenberg-form-selector-wrap",
                    className: "ydn-gutenberg-form-selector-wrapper"
                }, React.createElement(SelectControl, {
                    key: "ydn-gutenberg-form-selector-select-control",
                    value: downloaderId,
                    options: downloaderOptions,
                    onChange: selectDownloade
                }), React.createElement(SelectControl, {
                    key: "ydn-gutenberg-form-selector-select-control",
                    onChange: selectDownloade
                })));
            }

            return jsx;
        },
        save: function save(props) {
            return '[ydn_downloader id="' + props.attributes.downloaderId + '"]';
        }
    });
};

jQuery(document).ready(function () {
    var block = new WpDownloaderBlock();
    block.init();
});