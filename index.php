<?
	require 'php-sdk/facebook.php';
	
	// Create our Application instance (replace this with your appId and secret).
	$facebook = new Facebook(array(
	  'appId'  => '457681004272032',
	  'secret' => '3e3d797f61d5d598ff6a07782c47a6c5',
	));
	$user = $facebook->getUser();
	if ($user) {
	  try {
	    // Proceed knowing you have a logged in user who's authenticated.
	    $user_profile = $facebook->api('/me');
	  } catch (FacebookApiException $e) {
	    error_log($e);
	    $user = null;
	  }
	}
?>
<HTML>
<HEAD>
<TITLE>Facebook PHP </TITLE>
</HEAD>
<BODY>
<h1>Hello World</h1>
<?
	if ($user) :
 		echo '<p>User ID: ', $user, '</p>';
 		$user_profile = $facebook->api('/me','GET');
        echo '<pre>', print_r($user_profile), '</pre>';
        
 		// although there is a logout url, it's not what we want.
 		$logoutUrl =$facebook->getLogoutUrl();
 		echo '<p><a href="', $logoutUrl,'>login</a></p>';
 		echo '<p><a href="logout.php">logout</a></p>';
    else: 
        $loginUrl = $facebook->getLoginUrl(array(
        	'display'=>'popup',
        	'redirect_uri' => 'http://apps.facebook.com/coder-mulan/'
        ));
        echo '<p><a href="', $loginUrl, '" target="_top">login</a></p>';
    endif;
 ?>
 </BODY>
 </HTML>
