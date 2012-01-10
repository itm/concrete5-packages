<?php

defined('C5_EXECUTE') or die(_("Access Denied."));

class ItmLdapPackage extends Package
{
	protected $pkgHandle = 'itm_ldap';
	protected $appVersionRequired = '5.3.3';
	protected $pkgVersion = '1.0';

	public function getPackageDescription()
	{
		return t("Installs the ITM LDAP extension.");
	}

	public function getPackageName()
	{
		return t("ITM LDAP extension.");
	}

	public function install()
	{
		$pkg = parent::install();

		Loader::model('single_page');

		// install pages
		$sp1 = SinglePage::add('/dashboard/itm_ldap', $pkg);
		$sp1->update(array('cName' => t('ITM LDAP'), 'cDescription' => t('LDAP settings and info')));

		// add ldap group
		Group::add('ldap', t('Includes all users from LDAP servers.'));
		
		// fields
		
		Loader::model('user_attributes');
		
		$akc = AttributeKeyCategory::getByHandle('user');

		ItmLdapPackage::addUserTextAttr('room_number', t('Room number'), $pkg);
		ItmLdapPackage::addUserTextAttr('telephone_number', t('Telephone number'), $pkg);
		ItmLdapPackage::addUserTextAttr('telefax_number', t('Telefax number'), $pkg);
		ItmLdapPackage::addUserTextAttr('consultation', t('Consultation'), $pkg);
		
		// legacy since group is used
//		UserAttributeKey::add('boolean', array(
//			'akHandle' => 'ldap_entry',
//			'akName' => t('This is an LDAP entry'), 'akIsSearchable' => true
//		), $pkg);
	}
	
	public static function addUserTextAttr($handle, $name, $pkg)
	{
		UserAttributeKey::add('text', array(
			'akHandle' => $handle,
			'akName' => $name, 'akIsSearchable' => true
		), $pkg);
	}
	
	

}

?>
