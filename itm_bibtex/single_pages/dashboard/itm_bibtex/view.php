<?php defined('C5_EXECUTE') or die(_("Access Denied.")); ?>
<?php
Loader::model('file_list');

$dh = Loader::helper('concrete/dashboard');
$uh = Loader::helper('concrete/urls');
$ih = Loader::helper('concrete/interface');
$form = Loader::helper('form');

$fl = new FileList();
$fl->filterByExtension('bib');
$bibFiles = $fl->get(1000);
$selectList = array();
array_walk($bibFiles, function($val, $key) use(&$selectList)
		{
			$selectList[(string) $val->fID] = $val->getApprovedVersion()->fvTitle . " (ID: " . $val->fID . ")";
		});
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
	<?php if (count($selectList)): ?>
	$(document).ready(function ()
	{
		BibFileEditor.loadFile();
	});
	<?php endif; ?>

</script>
<?php echo $dh->getDashboardPaneHeaderWrapper(t('Bib-File Editor'), false, false, !count($selectList), array(), Page::getByPath("/dashboard")); ?>
	<form>
		<div class="ccm-pane-body">
			<?php if (count($selectList)): ?>
			<div>
				<?= t('Select file:') ?>&nbsp;<?= $form->select('bibfiles', $selectList, $since, array('style' => 'width: 300px', 'onchange' => 'return BibFileEditor.loadFile();')) ?>
			</div>
			<?php else: ?>
			<p>
				There are currently no Bib files available.
			</p>
			<?php endif; ?>
			<div id="bibFileContainer"  style="margin-top: 20px; display: none">
				<p><?= $form->textarea('bibFileContent', array('style' => 'width: 100%; height: 400px', 'onchange' => 'BibFileEditor.setChanged(true)')) ?></p>
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
		<?php if (count($selectList)): ?>
		<div class="ccm-pane-footer">
			<div class="ccm-buttons">
				<input type="hidden" name="create" value="1" />
				<?= $ih->button_js(t('Save'), 'BibFileEditor.saveFile()', 'right', 'primary') ?>
			</div>	
		</div>
		<?php endif; ?>
	</form>
<?php echo $dh->getDashboardPaneFooterWrapper(!count($selectList)); ?>
