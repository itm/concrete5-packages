<?php
defined('C5_EXECUTE') or die(_("Access Denied."));

$emailIconUrl = PageTheme::getByHandle('itm_theme')->getThemeURL() . '/images/email.png';

$members = $this->controller->getGroupMembers();

if (!count($members)):
	?>
	<h1 style="color: red; font-weight: bold">
		<?php echo t('No user entries for given group.'); ?>
	</h1>
<?php else : ?>
	<?= empty($caption) ? '' : '<h2>' . t($caption) . '</h2>' ?>
	<table class="itmTable itmldapUsers">
		<tr>
			<th class="name">
				<?= t('Name') ?>
			</th>
			<th class="email">
				<?= t('E-Mail') ?>
			</th>
			<th class="phone">
				<?= t('Phone') ?>
			</th>
			<th class="room">
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
		//$email_description = $tmpemail[0] . "@itm..."; 
		$email_description = sprintf('<img src="%s" alt="%s" title="%s" width="20" height="20" style="border: 0"/>', $emailIconUrl, $user->uEmail, t('Send mail to ' . $name));

		?>
			<tr>
				<td class="name">
					<?= $link ? '<a href="'.$link.'">' . $ldapHelper->getFullName($user) . '</a>' : $ldapHelper->getFullName($user) ?>
					<br/>
					<?= empty($description) ? "" : "(" . $description . ")" ?>
				</td>

				<td class="email"><a href="mailto:<?= $user->uEmail ?>"><?= $email_description?></a></td>

				<td class="phone"><?= $user->getAttribute('telephone_number') ?></td>

				<td class="room"><?= $user->getAttribute('room_number') ?></td>
			</tr>
		<?php endforeach; ?>
	</table>
<?php endif; ?>
