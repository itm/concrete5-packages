<?php
defined('C5_EXECUTE') or die(_("Access Denied.")); 

if (!strlen($uName)) :
?>
<h1 style="color: red; font-weight: bold">
	<?php echo t('Please select a user.'); ?>
</h1>
<?php else : ?>
<h1><?= $userInfo->getAttribute('name') ?></h1>
<div>
	<?php
		if ($userInfo->hasAvatar())
		{
			$av = Loader::helper('concrete/avatar'); 
			$av->outputUserAvatar($userInfo, true);
		}
		else
		{
			echo  '<img src="' . DIR_REL . '/packages/itm_ldap/images/noavatar.png" width="150" style="border: 1px solid #e4e4dd; padding: 1px; margin-right: 5px; float: left" alt="'.t('No avatar available').'"/>';
		}
		
		$config = new Config();
		$config->setPackageObject('itm_ldap');
		$street = $config->get('ITM_LDAP_STREET');
		$streetNo = $config->get('ITM_LDAP_STREET_NO');
		$zip = $config->get('ITM_LDAP_ZIP');
		$city = $config->get('ITM_LDAP_CITY');
		$uniLinkText = $config->get('ITM_LDAP_UNI_LINKTEXT');
		$uniUrl =  $config->get('ITM_LDAP_UNI_LINK');
		$instLinkText = $config->get('ITM_LDAP_INST_LINKTEXT');
		
		$name = $userInfo->getAttribute('name');
		$room = $userInfo->getAttribute('room_number');
		$phone = $userInfo->getAttribute('telephone_number');
		$fax = $userInfo->getAttribute('telefax_number');
		$title = $userInfo->getAttribute('title');
		$email = $userInfo->uEmail;
		$consultation = $userInfo->getAttribute('consultation');
		
		if ($name != '')
		{
			echo ($title == '' ? '' : "$title ") . "$name<br/>";
		}
		else
		{
			echo Page::getCurrentPage().getCollectionName()."<br/>";
		}
		echo "<a href=\"$uniUrl\" target=\"_blank\">$uniLinkText</a><br/>";
		echo "$instLinkText<br/>";
		echo "$street $streetNo<br/>";
		echo $room == '' ? '' : ("$room<br/>");
		echo "$zip $city<br/><br/>";
		echo $phone == '' ? '' : t('Phone:')." $phone<br/>";
		echo $fax == '' ? '' : t('Fax:')." $fax<br/>";
		echo $email == '' ? '' : t('E-Mail:')." $email<br/>";
		echo $consultation == '' ? '' : t('Consultation:')." $consultation";
	?>
	
</div>
<?php endif; ?>
