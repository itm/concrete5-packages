<?php

defined('C5_EXECUTE') or die(_("Access Denied."));

class DashboardItmLdapConfigController extends Controller
{

	public function view()
	{
		//load and save config data
		
		$config = new Config();
		$config->setPackageObject(Package::getByID($this->c->pkgID));
		$street = $config->get('ITM_LDAP_STREET');
		$streetNo = $config->get('ITM_LDAP_STREET_NO');
		$zip = $config->get('ITM_LDAP_ZIP');
		$city = $config->get('ITM_LDAP_CITY');
		$uniLinkText = $config->get('ITM_LDAP_UNI_LINKTEXT');
		$uniUrl = $config->get('ITM_LDAP_UNI_LINK');
		$instLinkText = $config->get('ITM_LDAP_INST_LINKTEXT');

		if (isset($_POST['ccm-submit-itm_ldap_config_form']))
		{
			$street = $this->post('ITM_LDAP_STREET');
			$streetNo = $this->post('ITM_LDAP_STREET_NO');
			$zip = $this->post('ITM_LDAP_ZIP');
			$city = $this->post('ITM_LDAP_CITY');
			$uniLinkText = $this->post('ITM_LDAP_UNI_LINKTEXT');
			$uniUrl = $this->post('ITM_LDAP_UNI_LINK');
			$instLinkText = $this->post('ITM_LDAP_INST_LINKTEXT');

			$config->save('ITM_LDAP_STREET', $street);
			$config->save('ITM_LDAP_STREET_NO', $streetNo);
			$config->save('ITM_LDAP_ZIP', $zip);
			$config->save('ITM_LDAP_CITY', $city);
			$config->save('ITM_LDAP_UNI_LINKTEXT', $uniLinkText);
			$config->save('ITM_LDAP_UNI_LINK', $uniUrl);
			$config->save('ITM_LDAP_INST_LINKTEXT', $instLinkText);

			$this->set('message', 'Configuration settings saved.');
			unset($_POST);
		}

		$this->set('street', $street);
		$this->set('streetNo', $streetNo);
		$this->set('zip', $zip);
		$this->set('city', $city);
		$this->set('uniLinkText', $uniLinkText);
		$this->set('uniUrl', $uniUrl);
		$this->set('instLinkText', $instLinkText);
	}

}
