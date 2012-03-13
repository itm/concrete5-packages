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
			<th class="avatar">
				<!-- void -->
			</th>
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
				<td class="avatar">
					<?php
					if ($user->hasAvatar())
					{
						echo ($link ? '<a href="'.$link.'">' : '') . '<img src="' . Loader::helper('concrete/avatar')->getImagePath($user) . '" width="30" alt="Portrait of ' . $ldapHelper->getFullName($user) . '"/>' . ($link ? '</a>' : '');
					}
					else
					{
						echo ($link ? '<a href="'.$link.'">' : '') . '<img src="' . DIR_REL . '/packages/itm_ldap/images/noavatar.png" width="26" style="border: 1px solid #e4e4dd; padding: 1px;" alt="' . t('Portrait not available') . '"/>' . ($link ? '</a>' : '');
					}
				?>
				</td>
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
