<?php  defined('C5_EXECUTE') or die(_("Access Denied."));

class LdapAuthPackage extends Package {

	protected $pkgHandle = 'ldap_auth';
	protected $appVersionRequired = '5.4.0';
	protected $pkgVersion = '1.0';
	
	public function getPackageDescription() {
		return t("Authenticate users via Active Directory or any LDAP compliant user directory.");
	}
	
	public function getPackageName() {
		return t("LDAP Authentication");
	}
	
	public function install() {
		$pkg = parent::install();
		Loader::model('single_page');
		$sp = SinglePage::add('ldap_login', $pkg);
		$sp = SinglePage::add('dashboard/users/ldap', $pkg);
		$sp->update(array('cName' => 'LDAP'));
		
		$config = new Config;
		$config->setPackageObject($pkg);
		$config->save('LDAP_HOST', NULL);
		$config->save('LDAP_DOMAIN_NAME', NULL);
		$config->save('LDAP_BASE', NULL);
		$config->save('LDAP_FILTER', NULL);
		$config->save('LDAP_GROUP_IMPORT_PREFIX', NULL);
		
		Config::save('ENABLE_REGISTRATION', false);
		Config::save('ENABLE_OPENID_AUTHENTICATION', false);
		Config::save('USER_REGISTRATION_WITH_EMAIL_ADDRESS', false);
		
		Loader::model('job');
		Job::installByPackage('poll_ldap_users', $pkg);
		
		Cache::flush();
	}
	
	public function on_start() {
		Events::extend('on_start', 'LoginRedirect', 'redirect', DIRNAME_PACKAGES . '/' . $this->pkgHandle . '/models/login_redirect.php');
	}
}
