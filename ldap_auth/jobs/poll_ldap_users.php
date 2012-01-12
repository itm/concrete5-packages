<?
/**
*
* Responsible for loading the indexed search class and initiating the reindex command.
* @package Utilities
*/

defined('C5_EXECUTE') or die("Access Denied.");
class PollLdapUsers extends Job {

	public function getJobName() {
		return t('Poll LDAP Users');
	}
	
	public function getJobDescription() {
		return t("Get all available users using current LDAP settings, and add or update the c5 userbase.");
	}
	
	function run() {
		$ldap = Loader::helper('ldap_authenticator', 'ldap_auth')->getUserList(Config::get('LDAP_JOB_USER'), Config::get('LDAP_JOB_PASSWORD'));
		
		if(is_array($ldap['return'])) foreach($ldap['return'] as $user) {
			if(!$user['mail']) $user['mail'] = $user['sAMAccountName'].'@'.Config::get('LDAP_DOMAIN_NAME');
			Loader::helper('ldap_authenticator', 'ldap_auth')->register($user, mt_rand());
		}
	}

}

?>