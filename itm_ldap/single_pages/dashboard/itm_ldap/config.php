<?php defined('C5_EXECUTE') or die("Access Denied."); ?>
<?php
$form = Loader::helper('form');
$ih = Loader::helper('concrete/interface');
$dh = Loader::helper('concrete/dashboard');
?>

	<?php echo $dh->getDashboardPaneHeaderWrapper(t("Common information for user blocks."), false, false, false, array(), Page::getByPath("/dashboard")); ?>
		<style type="text/css">
			#ldap_form table td
			{
				vertical-align: middle;
			}

			#ldap_form td:first-child, #ldap_form th:first-child
			{
				text-align: right
			}
		</style>
		<form method="POST" id="ldap_form" action="<?= View::url($this->getCollectionObject()->getCollectionPath()); ?>">
			<div class="ccm-pane-body">
				<table width="100%" cellspacing="5" cellpadding="0" class="zebra-striped">
					<thead>
						<tr>
							<th>Caption</th>
							<th>Value</th>
							<th>Default value</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td><?php echo t('Street name:') ?></td>
							<td><?php echo $form->text('ITM_LDAP_STREET', $street, array('style' => 'width: 200px')) ?></td>
							<td><?=t('Ratzeburger Allee')?></td>

						</tr>
						<tr>
							<td><?php echo t('Street:') ?></td>
							<td><?php echo $form->text('ITM_LDAP_STREET_NO', $streetNo, array('style' => 'width: 200px')) ?></td>
							<td>160</td>
						</tr>
						<tr>
							<td><?php echo t('ZIP code:') ?></td>
							<td><?php echo $form->text('ITM_LDAP_ZIP', $zip, array('style' => 'width: 200px')) ?></td>
							<td>23538</td>
						</tr>
						<tr>
							<td><?php echo t('City:') ?></td>
							<td><?php echo $form->text('ITM_LDAP_CITY', $city, array('style' => 'width: 200px')) ?></td>
							<td><?=t('Lübeck')?></td>
						</tr>
						<tr>
							<td><?php echo t('University caption:') ?></td>
							<td><?php echo $form->text('ITM_LDAP_UNI_LINKTEXT', $uniLinkText, array('style' => 'width: 200px')) ?></td>
							<td><?=t('University of Lübeck')?></td>
						</tr>
						<tr>
							<td><?php echo t('University URL:') ?></td>
							<td><?php echo $form->text('ITM_LDAP_UNI_LINK', $uniUrl, array('style' => 'width: 200px')) ?></td>
							<td><?=t('http://www.uni-luebeck.de')?></td>
						</tr>
						<tr>
							<td><?php echo t('Institute caption:') ?></td>
							<td><?php echo $form->text('ITM_LDAP_INST_LINKTEXT', $instLinkText, array('style' => 'width: 200px')) ?></td>
							<td><?=t('Institute of Telematics')?></td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="ccm-pane-footer">
				<div class="ccm-buttons">
					<input type="hidden" name="create" value="1" />
					<?php print $ih->submit('Save', 'itm_ldap_config_form', 'right', 'primary')?>
				</div>	
			</div>
		</form>
	<?php echo $dh->getDashboardPaneFooterWrapper(false); ?>
	