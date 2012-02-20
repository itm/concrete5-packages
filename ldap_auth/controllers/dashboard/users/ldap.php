<?php defined('C5_EXECUTE') or die("Access Denied.");

class DashboardUsersLdapController extends Controller {

	public function on_start() {
		$this->set('form', Loader::helper('form'));
		$this->set('ih', Loader::helper('concrete/interface'));
	}
	public function view() {
		$config = new Config;
		$config->setPackageObject(Package::getByID($this->c->pkgID));
		$host	= $config->get('LDAP_HOST');
		$domain	= $config->get('LDAP_DOMAIN_NAME');
		$baseStaff	= $config->get('LDAP_BASE_STAFF');
		$baseGroups	= $config->get('LDAP_BASE_GROUPS');
		$filter	= $config->get('LDAP_FILTER');
		$prefix	= $config->get('LDAP_GROUP_IMPORT_PREFIX');
		if($_POST && !$_POST['ldap_test'])
		{
			$host = $_POST['LDAP_HOST'];
			$config->save('LDAP_HOST', $host);
			if(!$host) $config->clear('LDAP_HOST');
			$domain = $_POST['LDAP_DOMAIN_NAME'];
			$config->save('LDAP_DOMAIN_NAME', $domain);
			if(!$domain) $config->clear('LDAP_DOMAIN_NAME');
			$baseStaff = $_POST['LDAP_BASE_STAFF'];
			$config->save('LDAP_BASE_STAFF', $baseStaff);
			if(!$baseStaff) $config->clear('LDAP_BASE_STAFF');
			$baseGroups = $_POST['LDAP_BASE_GROUPS'];
			$config->save('LDAP_BASE_GROUPS', $baseGroups);
			if(!$baseGroups) $config->clear('LDAP_BASE_GROUPS');
			$filter = $_POST['LDAP_FILTER'];
			$config->save('LDAP_FILTER', $filter);
			if(!$filter) $config->clear('LDAP_FILTER');
			$prefix = $_POST['LDAP_GROUP_IMPORT_PREFIX'];
			$config->save('LDAP_GROUP_IMPORT_PREFIX', $prefix);
			if(!$prefix) $config->clear('LDAP_FILTER');
			$this->set('message', 'Configuration settings saved.');
		}
		unset($_POST);
		$this->set('host', $host);
		$this->set('domain', $domain);
		$this->set('baseStaff', $baseStaff);
		$this->set('baseGroups', $baseGroups);
		$this->set('filter', $filter);
		$this->set('prefix', $prefix);
		
		$this->set('jobUser', $config->get('LDAP_JOB_USER'));
		$this->set('jobPassword', $config->get('LDAP_JOB_PASSWORD'));
	}
	
	public function test() {
		$config = new Config;
		$config->setPackageObject(Package::getByID($this->c->pkgID));
		$config->save('LDAP_JOB_USER', $this->post('uName'));
		$config->save('LDAP_JOB_PASSWORD', $this->post('uPassword'));
		
		$ldap = Loader::helper('ldap_authenticator', 'ldap_auth')->getUserList($this->post('uName'), $this->post('uPassword'));
		$this->set('ldap_return', $ldap['return']);
		$this->set('message', $ldap['message']);
		$this->set('error', $ldap['errors']);
		$this->view();
	}
	
	public function import() {
		$ldap = Loader::helper('ldap_authenticator', 'ldap_auth');
		$list = $ldap->getUserList($this->post('uName'), $this->post('uPassword'));
		foreach($list as $userData) {
			$ldap->register($userData);
		}
	}
}
