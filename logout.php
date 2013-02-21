<?
	require 'php-sdk/facebook.php';
	
	// Create our Application instance (replace this with your appId and secret).
	$facebook = new Facebook(array(
	  'appId'  => '457681004272032',
	  'secret' => '3e3d797f61d5d598ff6a07782c47a6c5',
	));
	
	setCookie('fbs_'.$facebook->getAppId(), '', time()-100, '/', 'jianping-roth-php.herokuapp.com/');
	$facebook->destroySession();
	header('Location: index.php');
?>
