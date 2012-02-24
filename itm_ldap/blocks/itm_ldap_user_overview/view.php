<?php
defined('C5_EXECUTE') or die(_("Access Denied."));

$members = $this->controller->getGroupMembers();

if (!count($members)):
	?>
	<h1 style="color: red; font-weight: bold">
		<?php echo t('No user entries for given group.'); ?>
	</h1>
<?php else : ?>
	<?= empty($caption) ? '' : '<h2>' . t($caption) . '</h2>' ?>
	<table class="itmTable">
		<tr>
			<th>
				<?= t('Name') ?>
			</th>
			<th>
				<?= t('E-Mail') ?>
			</th>
			<th>
				<?= t('Phone') ?>
			</th>
			<th>
				<?= t('Room') ?>
			</th>
		</tr>
		<?php foreach ($members as $user): ?>
		<?php
		$ldapHelper = Loader::helper('itm_ldap', 'itm_ldap');
		$link = $ldapHelper->getUserPageLink($user->uName);
		$name = $user->getAttribute('name');
		$description = $user->getAttribute('description');


		$tmpemail=split("@",$user->uEmail);
		$email_description = $tmpemail[0] . "@itm..."; 

		?>
			<tr>
				<td>
					<?= $link ? '<a href="'.$link.'">' . $ldapHelper->getFullName($user) . '</a>' : $ldapHelper->getFullName($user) ?>
					<br/>
					<?= empty($description) ? "" : "(" . $description . ")" ?>
				</td>

				<td><a href="mailto:<?= $user->uEmail ?>"><?= $email_description?></a></td>

				<td><?= $user->getAttribute('telephone_number') ?></td>

				<td><?= $user->getAttribute('room_number') ?></td>
			</tr>
		<?php endforeach; ?>
	</table>
<?php endif; ?>
