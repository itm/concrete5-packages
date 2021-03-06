<?php  defined('C5_EXECUTE') or die("Access Denied."); ?>

<?php  $th = $c->getCollectionThemeObject(); ?>
<?php  $this->inc('editor_config.php', array('theme' => $th)); ?> 

<script type="text/javascript">
var switchIcon = '<img src="<?= ASSETS_URL_IMAGES ?>/icons/edit_small.png" width="16" height="16" alt="<?= t('Switch Edit Mode') ?>" title="<?= t('Switch Edit Mode') ?>" style="vertical-align: middle"/>';
// TODO: hard coded - not kind... but functional.
var items =
[
	'Place and Time',
	'Audience',
	'Begin',
	'Topics',
	'Preliminaries',
	'Literature',
	'Links',
	'Documents',
	'Exercise Documents',
	'Voluntary Exercises',
	'Hint',
	'Maximum Number of Participants'
];

var ccm_editorCurrentAuxTool = '';
var editor_id = 'ccm-content-<?php echo $a->getAreaID()?>';

// store the selection/position for ie..
var bm; 
setBookMark = function () {
	tinyMCE.activeEditor.focus();
	bm = tinyMCE.activeEditor.selection.getBookmark();
}

ccm_selectSitemapNode = function(cID, cName) {
	var mceEd = tinyMCE.activeEditor;	
	var url = '<?php echo BASE_URL . DIR_REL?>/<?php echo DISPATCHER_FILENAME?>?cID=' + cID;
	
	mceEd.selection.moveToBookmark(bm);
	var selectedText = mceEd.selection.getContent();
	
	if (selectedText != '') {		
		mceEd.execCommand('mceInsertLink', false, {
			href : url,
			title : cName,
			target : null,
			'class' : null
		});
	} else {
		var selectedText = '<a href="<?php echo BASE_URL . DIR_REL?>/<?php echo DISPATCHER_FILENAME?>?cID=' + cID + '" title="' + cName + '">' + cName + '<\/a>';
		tinyMCE.execCommand('mceInsertRawHTML', false, selectedText, true); 
	}
	
}

ccm_chooseAsset = function(obj) {
	var mceEd = tinyMCE.activeEditor;
	mceEd.selection.moveToBookmark(bm); // reset selection to the bookmark (ie looses it)

	switch(ccm_editorCurrentAuxTool) {
		case "image":
			var args = {};
			tinymce.extend(args, {
				src : obj.filePathInline,
				alt : obj.title,
				width : obj.width,
				height : obj.height
			});
			
			mceEd.execCommand('mceInsertContent', false, '<img id="__mce_tmp" src="javascript:;" />', {skip_undo : 1});
			mceEd.dom.setAttribs('__mce_tmp', args);
			mceEd.dom.setAttrib('__mce_tmp', 'id', '');
			mceEd.undoManager.add();
			break;
		default: // file
			var selectedText = mceEd.selection.getContent();
			
			if(selectedText != '') { // make a link, let mce deal with the text of the link..
				mceEd.execCommand('mceInsertLink', false, {
					href : obj.filePath,
					title : obj.title,
					target : null,
					'class' :  null
				});
			} else { // insert a normal link
				var html = '<a href="' + obj.filePath + '">' + obj.title + '<\/a>';
				tinyMCE.execCommand('mceInsertRawHTML', false, html, true); 
			}
		break;
	}
}
</script>

<?php  Loader::element('editor_controls'); ?>