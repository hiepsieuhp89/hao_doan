/**
 * @license Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
	var domainer=location.hostname;
        config.protectedSource = [/\r|\n/g];
	config.removeDialogTabs = 'image:advanced;link:advanced';
	config.filebrowserBrowseUrl = 'http://giadung88.com/public/ckfinder/ckfinder.html';

config.filebrowserImageBrowseUrl = 'http://giadung88.com/public/ckfinder/ckfinder.html?type=Images';

config.filebrowserFlashBrowseUrl = 'http://giadung88.com/demo/public/ckfinder/ckfinder.html?type=Flash';

config.filebrowserUploadUrl ='http://giadung88.com/public/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files';

config.filebrowserImageUploadUrl = 'http://giadung88.com/public/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images';

config.filebrowserFlashUploadUrl = 'http://giadung88.com/public/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash';
config.entities = false;
config.htmlEncodeOutput = false;
};
