<?php

defined('C5_EXECUTE') or die(_("Access Denied."));

class ItmLdapPackage extends Package
{
	protected $pkgHandle = 'itm_ldap';
	protected $appVersionRequired = '5.3.3';
	protected $pkgVersion = '1.0';

	public function getPackageDescription()
	{
		return t("Installs the ITM LDAP Extension.");
	}

	public function getPackageName()
	{
		return t("ITM LDAP Extension.");
	}

	public function install()
	{
		$pkg = parent::install();

		Loader::model('single_page');

		// install pages
		$sp1 = SinglePage::add('/dashboard/itm_ldap', $pkg);
		$sp1->update(array('cName' => t('ITM LDAP'), 'cDescription' => t('LDAP sync and config')));

		$sp2 = SinglePage::add('dashboard/itm_ldap/synchronization', $pkg);
		$sp2->update(array('cName' => t('Synchronization')));

		$sp3 = SinglePage::add('dashboard/itm_ldap/config', $pkg);
		$sp3->update(array('cName' => t('Configuration')));

		// install block
		BlockType::installBlockTypeFromPackage('itm_ldap_user', $pkg);
		BlockType::installBlockTypeFromPackage('itm_ldap_user_overview', $pkg);

		// install page type
		Loader::model('collection_types');
		$ctItmLdapUserPage = CollectionType::getByHandle('itm_ldap_user');
		if (!$ctItmLdapUserPage || !intval($ctItmLdapUserPage->getCollectionTypeID()))
		{
			$ctItmLdapUserPage = CollectionType::add(array('ctHandle' => 'itm_ldap_user_page', 'ctName' => t('ITM LDAP User')), $pkg);
		}

		// add default attribute
		$ctItmLdapUserPage->assignCollectionAttribute(CollectionAttributeKey::getByHandle('exclude_nav'));

		// install default page of itm_ldap_user_page page type
		// this includes setting up a default itm_ldap_user_entry block,
		// obtain master template
		$mTplItmLdapUserPage = $ctItmLdapUserPage->getMasterTemplate();

		// create content area within master template
		$aUserInformation = Area::getOrCreate($mTplItmLdapUserPage, 'User Information');

		// create data array that is passed to addBlock() - what data ever...
		$data = array();

		// get LDAP user block type
		$btLdapUser = BlockType::getByHandle("itm_ldap_user");

		// set default data for thesis entry block, add and save it
		$defaultLdapUserData = array(
			'uName' => '',
		);

		$bLdapUserData = $mTplItmLdapUserPage->addBlock($btLdapUser, $aUserInformation, $data);
		$bLdapUserData->getController()->save($defaultLdapUserData);

		ItmThemePackage::addBreadcrumbsBlock($mTplItmLdapUserPage);
		ItmThemePackage::addNavigationBlock($mTplItmLdapUserPage);

		// add ldap group
		try
		{
			Group::add('ldap', t('Includes all users from LDAP servers.'));
		}
		catch (Exception $e)
		{
			// ignore
		}

		// fields

		Loader::model('user_attributes');

		$akc = AttributeKeyCategory::getByHandle('user');

		ItmLdapPackage::setupLdapAttributes($pkg);
		
		ItmLdapPackage::setupConfig($pkg);
	}

	public static function addUserTextAttr($handle, $name, $pkg)
	{
		UserAttributeKey::add('text', array(
			'akHandle' => $handle,
			'akName' => $name, 'akIsSearchable' => true
				), $pkg);
	}

	public static function setupLdapAttributes($pkg)
	{
		ItmLdapPackage::addUserTextAttr('room_number', t('Room number'), $pkg);
		ItmLdapPackage::addUserTextAttr('telephone_number', t('Telephone number'), $pkg);
		ItmLdapPackage::addUserTextAttr('telefax_number', t('Telefax number'), $pkg);
		ItmLdapPackage::addUserTextAttr('consultation', t('Consultation'), $pkg);
		ItmLdapPackage::addUserTextAttr('icq_number', t('ICQ number'), $pkg);
		ItmLdapPackage::addUserTextAttr('skype_number', t('Skype number'), $pkg);
		ItmLdapPackage::addUserTextAttr('name', t('Name'), $pkg);
		ItmLdapPackage::addUserTextAttr('title', t('Title'), $pkg);
		ItmLdapPackage::addUserTextAttr('staff_group', t('Staff group'), $pkg);
	}
	
	public static function setupConfig($pkg)
	{
		$config = new Config();
		$config->setPackageObject($pkg);
		$config->save('ITM_LDAP_STREET', t('Ratzeburger Allee'));
		$config->save('ITM_LDAP_STREET_NO', '160');
		$config->save('ITM_LDAP_ZIP', '23538');
		$config->save('ITM_LDAP_CITY', t('Lübeck'));
		$config->save('ITM_LDAP_UNI_LINKTEXT', 'University of Lübeck');
		$config->save('ITM_LDAP_UNI_LINK', 'http://www.uni-luebeck.de');
		$config->save('ITM_LDAP_INST_LINKTEXT', 'Institute of Telematics');
	}

}

?>
