<?php

defined('C5_EXECUTE') or die(_("Access Denied."));

class DashboardItmLdapController extends Controller
{
	public function view()
	{
		if (!$this->hasLdapAuth())
		{
			$this->redirect('/dashboard/itm_ldap?task=update_new_users');
		}
	}
	
	public function hasLdapAuth()
	{
		$ldapAuthPkg = Package::getByHandle('ldap_auth');
		return !empty($ldapAuthPkg);
	}
	
	public function listStaff()
	{

		
	}

	public function update_new_users()
	{
		
		$this->render('/dashboard/../../../packages/single_pages/dashboard/itm_ldap/noldapauth');
	}
	
	public static function ldapBindStaff()
	{
		$ldap = NewADOConnection('ldap');
		global $LDAP_CONNECT_OPTIONS;
		$LDAP_CONNECT_OPTIONS = array(
			array("OPTION_NAME" => LDAP_OPT_DEREF, "OPTION_VALUE" => 2),
			array("OPTION_NAME" => LDAP_OPT_SIZELIMIT, "OPTION_VALUE" => 100),
			array("OPTION_NAME" => LDAP_OPT_TIMELIMIT, "OPTION_VALUE" => 30),
			array("OPTION_NAME" => LDAP_OPT_PROTOCOL_VERSION, "OPTION_VALUE" => 3),
			array("OPTION_NAME" => LDAP_OPT_ERROR_NUMBER, "OPTION_VALUE" => 13),
			array("OPTION_NAME" => LDAP_OPT_REFERRALS, "OPTION_VALUE" => false),
			array("OPTION_NAME" => LDAP_OPT_RESTART, "OPTION_VALUE" => false)
		);
		
		if (!$ldap->Connect('ldap.itm.uni-luebeck.de', '', '', 'ou=Staff,ou=People,dc=itm,dc=uni-luebeck,dc=de'))
		{
			return false;
		}

		$ldap->SetFetchMode(ADODB_FETCH_ASSOC);
		
		return $ldap;		
	}
	
	public function getCachedUsers()
	{
		$ldap = $this->ldapBindStaff();
		if (!$ldap)
		{
			throw new Exception(t('LDAP connection failed!'));
		}
		
		$filter = '(uid=*)';
		
		$result = $ldap->GetArray($filter);
		
		$ldap->close();
		
		return $result;
	}
	
	public function update()
	{
		$ldap = $this->ldapBindStaff();
		if (!$ldap)
		{
			throw new Exception(t('LDAP connection failed!'));
		}
		
		$GLOBALS['ldap_return'] = $ldap->GetArray('(uid=*)');
		
		$ldap->Close();
	}
	
	

}

?>