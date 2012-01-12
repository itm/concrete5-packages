<?php
defined('C5_EXECUTE') or die(_("Access Denied."));

class DashboardItmLdapController extends Controller
{
	public function __construct()
	{
		parent::__construct();
		
		var_dump("ICH WERDE GEBOREN");
	}
	
	public function view()
	{
		// directly visit synchronization page
		$this->redirect('/dashboard/itm_ldap/synchronization');
	}
}
?>
