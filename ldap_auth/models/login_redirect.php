<?php  defined('C5_EXECUTE') or die("Access Denied.");

class LoginRedirect {
	
	public function redirect() {
		//get the current page
		$page = Page::getCurrentPage();
		if($page->cPath == '/login') {
			$path = explode('/', strstr($_SERVER['REQUEST_URI'], 'login'));
			unset($path[0]);
			$path = array_filter($path);
			$path = implode('/', $path);
			if($_POST) {
				foreach($_POST as $key => $value) {
					$post[] = $key.'='.$value;
				}
				$post = implode('&', $post);
				header("POST ".View::url('ldap_login')." HTTP/1.1\r\n");
				header("Content-Length: ".strlen($post)."\r\n\r\n");
				header($post);
			} else {
				$cnt = Loader::controller(NULL);
				$cnt->redirect('ldap_login', $path);
			}
		}
	}
}

?>
