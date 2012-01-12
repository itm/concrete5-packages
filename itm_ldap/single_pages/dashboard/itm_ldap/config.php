<?php defined('C5_EXECUTE') or die("Access Denied."); ?>
<?php
$form = Loader::helper('form');
$ih = Loader::helper('concrete/interface');
?>
<div style="width: 400px">
	<h1><span><?php echo t("Common information for user blocks.") ?></span></h1>
	<div class="ccm-dashboard-inner">
		<form method="POST" id="ldap_form" action="<?= View::url($this->getCollectionObject()->getCollectionPath()); ?>">
			<table width="100%" border="0" cellspacing="5" cellpadding="0">
				<tr>
					<td><?php echo $form->label('ITM_LDAP_STREET', t('Street name:')) ?></td>
					<td><?php echo $form->text('ITM_LDAP_STREET', $street, array('style' => 'width: 200px')) ?></td>
				</tr>
				<tr>
					<td></td>
					<td><small><em>Default value is <b><?=t('Ratzeburger Allee')?></b></small></td>
				</tr>
				<tr>
					<td><?php echo $form->label('ITM_LDAP_STREET_NO', t('Street:')) ?></td>
					<td><?php echo $form->text('ITM_LDAP_STREET_NO', $streetNo, array('style' => 'width: 200px')) ?></td>
				</tr>
				<tr>
					<td></td>
					<td><small><em>Default value is <b>160</b></small></td>
				</tr>
				<tr>
					<td><?php echo $form->label('ITM_LDAP_ZIP', t('ZIP code:')) ?></td>
					<td><?php echo $form->text('ITM_LDAP_ZIP', $zip, array('style' => 'width: 200px')) ?></td>
				</tr>
				<tr>
					<td></td>
					<td><small><em>Default value is <b>23538</b></small></td>
				</tr>
				<tr>
					<td><?php echo $form->label('ITM_LDAP_CITY', t('City:')) ?></td>
					<td><?php echo $form->text('ITM_LDAP_CITY', $city, array('style' => 'width: 200px')) ?></td>
				</tr>
				<tr>
					<td></td>
					<td><small><em>Default value is <b><?=t('Lübeck')?></b></small></td>
				</tr>
				<tr>
					<td><?php echo $form->label('ITM_LDAP_UNI_LINKTEXT', t('University caption:')) ?></td>
					<td><?php echo $form->text('ITM_LDAP_UNI_LINKTEXT', $uniLinkText, array('style' => 'width: 200px')) ?></td>
				</tr>
				<tr>
					<td></td>
					<td><small><em>Default value is <b><?=t('University of Lübeck')?></b></small></td>
				</tr>
				<tr>
					<td><?php echo $form->label('ITM_LDAP_UNI_LINK', t('University URL:')) ?></td>
					<td><?php echo $form->text('ITM_LDAP_UNI_LINK', $uniUrl, array('style' => 'width: 200px')) ?></td>
				</tr>
				<tr>
					<td></td>
					<td><small><em>Default value is <b><?=t('http://www.uni-luebeck.de')?></b></small></td>
				</tr>
				<tr>
					<td><?php echo $form->label('ITM_LDAP_INST_LINKTEXT', t('Institute caption:')) ?></td>
					<td><?php echo $form->text('ITM_LDAP_INST_LINKTEXT', $instLinkText, array('style' => 'width: 200px')) ?></td>
				</tr>
				<tr>
					<td></td>
					<td><small><em>Default value is <b><?=t('Institute of Telematics')?></b></small></td>
				</tr>
			</table>
			<?php print $ih->submit('Save', 'itm_ldap_config_form') ?>
			<div class="ccm-spacer">&nbsp;</div>
		</form>
	</div>
</div>