<?php
defined('C5_EXECUTE') or die("Access Denied.");
$dh = Loader::helper('concrete/dashboard');
?>
<?php echo $dh->getDashboardPaneHeaderWrapper(t("Basic Authentication Configuration"), false, false, false, array(), Page::getByPath("/dashboard")); ?>
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
	<form method="POST" id="ldap_form" action="<?=View::url($this->getCollectionObject()->getCollectionPath());?>">
		<div class="ccm-pane-body">
			<table width="100%" border="0" cellspacing="5" cellpadding="0" class="zebra-striped">
				<thead>
					<tr>
						<th><?=t('Caption')?></th>
						<th><?=t('Value')?></th>
						<th><?=t('Example')?></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td><?php echo t('LDAP Host Name/Address:') ?></td>
						<td><?php echo $form->text('LDAP_HOST', $host, array('style' => 'width: 200px'))?></td>
						<td><small><em>server.domain.tld:port or 128.1.1.1:389</em></small></td>
					</tr>
					<tr>
						<td><?php echo t('Default Base Query:')?></td>
						<td><?php echo $form->text('LDAP_BASE', $base, array('style' => 'width: 200px'))?></td>
						<td><small><em>ou=People,o=Company,c=US</em></small></td>
					</tr>
					<tr>
						<td><?php echo t('Default Domain Name:')?></td>
						<td><?php echo $form->text('LDAP_DOMAIN_NAME', $domain, array('style' => 'width: 200px'))?></td>
						<td><small><em>domain.tld</em></small></td>
					</tr>
				</tbody>
			</table>
		</div>
		<div class="ccm-pane-footer">
			<div class="ccm-buttons">
				<input type="hidden" name="create" value="1" />
				<?php print $ih->submit('Save', 'ldap_form', 'right', 'primary')?>
			</div>	
		</div>
		
		<!-- No relevance for ITM
		<h1><span><?php echo t("Advanced Configuration")?></span></h1>
		<div class="ccm-dashboard-inner">
			<h2><span style="color:red"><?=t('WARNING')?>:</span> <?=t('Leave these settings alone if you don\'t know what you\'re doing!')?></h2>
				<table width="100%" border="0" cellspacing="5" cellpadding="0">
					<tr>
						<td><?php echo $form->label('LDAP_BASE', 'Default Base Query:')?></td>
						<td><?php echo $form->text('LDAP_BASE', $base, array('style' => 'width: 200px'))?></td>
					</tr>
					<tr>
						<td></td>
						<td><small><em>ou=People,o=Company,c=US</em></small></td>
					</tr>
					<tr>
						<td><?php echo $form->label('LDAP_FILTER', 'Criteria Filter:')?></td>
						<td><?php echo $form->textarea('LDAP_FILTER', $filter, array('style' => 'min-width:200px; max-width: 200px'))?></td>
					</tr>
					<tr>
						<td></td>
						<td><small><em>Default: (objectCategory=person)</em></small></td>
					</tr>

					<tr>
						<td><?php echo $form->label('LDAP_GROUP_IMPORT_PREFIX', 'Group Import Prefix:')?></td>
						<td><?php echo $form->text('LDAP_GROUP_IMPORT_PREFIX', $prefix, array('style' => 'width: 200px'))?></td>
					</tr>
					<tr>
						<td><small><em>"Domain Admins" are added to this site's "Administrators" group.</td>
						<td><small><em>Make sure to add a space after the prefix if<br/>necessary, or it will try to add users to groups<br/>with a space before their name.</em></small></td>
					</tr>
				</table>
				<?php print $ih->submit('Save', 'ldap_form')?>
				<div class="ccm-spacer">&nbsp;</div>
		</div>  -->
	</form>
<?php echo $dh->getDashboardPaneFooterWrapper(false); ?>

		<?php //removed $host from if-clause and replaced it by a "false" to prevent from showing following HTML  ?>
		<?php if(false) { ?>
		<td width="300px" valign="top" style="padding-left:15px;">
		<h1><span><?php echo t("Poll Users Job Authentication")?></span></h1>
			<div class="ccm-dashboard-inner">
				<form method="POST" id="ldap_test" action="<?=View::url($this->getCollectionObject()->getCollectionPath(), 'test');?>">
					<?php echo $form->hidden('ldap_test', '1');?>
					<table width="100%" border="0" cellspacing="5" cellpadding="0">
						<tr>
							<td><?php echo $form->label('uName', 'Username:')?></td>
							<td><?php echo $form->text('uName', $jobUser, array('style' => 'width: 150px'))?></td>
						</tr>
						<tr>
							<td><?php echo $form->label('uPassword', 'Password:')?></td>
							<td><?php echo $form->password('uPassword', $jobPassword, array('style' => 'width: 150px'))?></td>
						</tr>
					</table>
					<?php print $ih->submit('Save and Test', 'ldap_test');?>
				</form>
				<div class="ccm-spacer">&nbsp;</div>
			</div>
			<?php if($this->controller->getTask() == 'test') { ?>
			<h1><span><?php echo t("User Query")?></span></h1>
			<div class="ccm-dashboard-inner" style="padding-top:0; padding-right:0; padding-bottom:0;"><?php if($ldap_return) { ?>
				<div style="max-height:300px; overflow-y:scroll; overflow-x:hidden"><?php foreach($ldap_return as $item) { ?>
					<?php 
						$ui = UserInfo::getByUserName($item['sAMAccountName']);
						if(is_object($ui)) { ?><a href="<?=View::url('dashboard/users/search')?>?uID=<?=$ui->uID?>"><?=$item['name'];?></a>
						<?php } else { ?><?=$item['name'];?><?php }?>
						<br />
					 <?php } ?>
				<?php } else { ?>
				<p>No results returned. Check errors above.</p>
				<?php } ?>
				<hr />
				<p>Query: <em>(&<?=Loader::helper('ldap_authenticator', 'ldap_auth')->getFilter()?>(!(description=Built-in*))(!(name=*Mailbox*))(!(name=*Email*)))</em></p><p>Human: Only user accounts that don't have "Built-in" in thier description and don't have Mailbox or Email anywhere in their name.</p>
			</div>
			<?php } ?>
		</td><?php } ?>
