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

<HTML>
<HEAD>
<TITLE> Hello World in PHP </TITLE>
</HEAD>
<BODY>
<?
// Hello world in PHP
 print("Hello World");
 echo 'User ID: ', $user, '</p>';
 ?>
 </BODY>
 </HTML>
