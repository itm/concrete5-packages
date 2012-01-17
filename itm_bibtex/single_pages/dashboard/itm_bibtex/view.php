<?php defined('C5_EXECUTE') or die(_("Access Denied.")); ?>
<?php
Loader::model('file_list');
$uh = Loader::helper('concrete/urls');
$ih = Loader::helper('concrete/interface');
$form = Loader::helper('form');
?>
<script language="JavaScript">
	BibFileEditor = {
		changed: false,
		selIndex: 0,
		loadFile: function()
		{
			if (this.changed && confirm('<?= t('Current file has been changed. Do you want to save it in advance?') ?>'))
			{
				if (!this.saveRequest() && !confirm('<?= t('Saving failed? Do you want to proceed anyway?') ?>'))
				{
					$('#bibfiles').get(0).selectedIndex = this.selIndex;
					return;
				}
			}
			
			$('#bibFileContainer').css('display', 'none');
			$('#bibFileLoading').css('display', 'block');
			
			$.get("<?= $uh->getToolsURL('loadbibfile', 'itm_bibtex'); ?>", { f: $('#bibfiles').val() }).success(function(data, stat)
			{	
				BibFileEditor.setChanged(false);
				BibFileEditor.selIndex = $('#bibfiles').get(0).selectedIndex;
				$('#bibFileContent').val(data);
				$('#bibFileLoading').css('display', 'none');
				$('#bibFileContainer').css('display', 'block');
				
			}).error(function()
			{
				BibFileEditor.setChanged(false);
				$('#bibFileLoading').css('display', 'none');
				alert('Selected file could not be loaded.');
			});
		},
		saveFile: function()
		{
			if (!this.changed)
			{
				alert('<?= t('No changes where noticed. Saving aborted.') ?>');
				return;
			}
			
			if (this.saveRequest())
			{
				alert('<?= t('File has been successfully saved.') ?>');
			}
			else
			{
				alert('<?= t('File could not be saved.') ?>');
			}
		},
		saveRequest: function()
		{			
			var succeed = false;
			
			$('#bibFileContainer').css('display', 'none');
			$('#bibFileSaving').css('display', 'block');
			
			$.ajax("<?= $uh->getToolsURL('savebibfile', 'itm_bibtex'); ?>", {
				data: { f: $('#bibfiles').val(), c: $('#bibFileContent').val() },
				async: false,
				type: 'POST',
				success: function(data)
				{
					succeed = true;
					BibFileEditor.setChanged(false);
				}});
		
			$('#bibFileSaving').css('display', 'none');
			$('#bibFileContainer').css('display', 'block');
			
			return succeed;
		},
		setChanged: function(doChange)
		{
			this.changed = doChange;
		}
	}
	
	$(document).ready(function ()
	{
		BibFileEditor.loadFile();
	});

</script>
<h1><span><?php echo t('Bib-File Editor') ?></span></h1>
<div class="ccm-dashboard-inner">
	<?php
	$fl = new FileList();
	$fl->filterByExtension('bib');
	$bibFiles = $fl->get(1000);
	$selectList = array();
	array_walk($bibFiles, function($val, $key) use(&$selectList)
			{
				$selectList[(string) $val->fID] = $val->getApprovedVersion()->fvTitle . " (ID: " . $val->fID . ")";
			});
	?>
	<div>
		<?= t('Select file:') ?>&nbsp;<?= $form->select('bibfiles', $selectList, $since, array('style' => 'width: 300px', 'onchange' => 'return BibFileEditor.loadFile();')) ?>
	</div>
	<div id="bibFileContainer"  style="margin-top: 20px; display: none">
		<form>
			<div><?= $form->textarea('bibFileContent', array('style' => 'width: 90%; height: 400px', 'onchange' => 'BibFileEditor.setChanged(true)')) ?></div>
			<p class="ccm-buttons">
				<?= $ih->button_js(t('Save'), 'BibFileEditor.saveFile()', 'left') ?>
			</p>
			<div class="ccm-spacer">&nbsp;</div>
		</form>
	</div>
	<div id="bibFileLoading" style="display: none; margin-top: 20px">
		<img src="<?= ASSETS_URL_IMAGES ?>/throbber_white_32.gif" width="32" height="32" alt="<?= t('Loading...') ?>" style="vertical-align: middle; margin-right: 10px"/>
		<?= t('Load file...') ?>
	</div>
	<div id="bibFileSaving" style="display: none; margin-top: 20px">
		<img src="<?= ASSETS_URL_IMAGES ?>/throbber_white_32.gif" width="32" height="32" alt="<?= t('Loading...') ?>" style="vertical-align: middle; margin-right: 10px"/>
		<?= t('Save file...') ?>
	</div>
</div>
