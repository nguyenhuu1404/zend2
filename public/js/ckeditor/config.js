/**
 * @license Copyright (c) 2003-2015, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here.
	// For complete reference see:
	// http://docs.ckeditor.com/#!/api/CKEDITOR.config

	// The toolbar groups arrangement, optimized for two toolbar rows.
	config.toolbarGroups = [
		{ name: 'clipboard',   groups: [ 'clipboard', 'undo' ] },
		{ name: 'editing',     groups: [ 'find', 'selection', 'spellchecker' ] },
		{ name: 'links' },
		{ name: 'insert' },
		{ name: 'forms' },
		{ name: 'tools' },
		{ name: 'document',	   groups: [ 'mode', 'document', 'doctools' ] },
		{ name: 'others' },
		'/',
		{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
		{ name: 'paragraph',   groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ] },
		{ name: 'styles' },
		{ name: 'colors' },
		{ name: 'about' }
	];

	// Remove some buttons provided by the standard plugins, which are
	// not needed in the Standard(s) toolbar.
	config.removeButtons = 'Underline,Subscript,Superscript';

	// Set the most common block elements.
	config.format_tags = 'p;h1;h2;h3;pre';

	// Simplify the dialog windows.
	config.removeDialogTabs = 'image:advanced;link:advanced';
	//config do rong cua box textarea
	config.width="600px";
	//config cho bo kcfinder
	//BASEPATH duoc khai bao trong file add.phtml la duong dan toi ckediter
   config.filebrowserBrowseUrl = BASEPATH + '/kcfinder/browse.php?opener=ckeditor&type=files';
   config.filebrowserImageBrowseUrl =BASEPATH + '/kcfinder/browse.php?opener=ckeditor&type=images';
   config.filebrowserFlashBrowseUrl = BASEPATH + '/kcfinder/browse.php?opener=ckeditor&type=flash';
   config.filebrowserUploadUrl = BASEPATH + '/kcfinder/upload.php?opener=ckeditor&type=files';
   config.filebrowserImageUploadUrl = BASEPATH + '/kcfinder/upload.php?opener=ckeditor&type=images';
   config.filebrowserFlashUploadUrl = BASEPATH + '/kcfinder/upload.php?opener=ckeditor&type=flash';	
};
