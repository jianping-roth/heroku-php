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
    <meta charset="utf-8" />
    <TITLE>Movie Recommendator</TITLE>
    <link rel="stylesheet" href="styles.css" />
</HEAD>
<BODY>
<?
	if ($user) :
 		echo '<p class="notes"><a href="logout.php">logout</p>';
 		$user_graph = $facebook->api('/me/friends','GET');
        $moviePath='me/friends?fields=movies.fields(likes,id,name,created_time,picture.width(100).height(100).type(square),link,description)';
        $movies_graph = $facebook->api($moviePath);

        echo '<div class="movegroupp">';
        foreach ($movies_graph['data'] as $key => $value) {
            echo '<div class="friendinfo group">';
            echo '<a href="http://facebook.com/', $value['id'], '" target="_top">';
            echo '<img class="frienddumb" src="https://graph.facebook.com/',
                $value['id'], '/picture" alt="',
                $value['name'], '"/>';
            echo '</a>';
            echo '<h2>', $value['name'], '</h2>';
            echo '<h3>Recommends</h3>';

            echo '<ul class="movies group">';

            foreach ($value['movies']['data'] as $moviekey => $movievalue) {
                echo '<li>';
                    echo '<div class="viewinfo">';
                     echo '<div class="wrapper>';
                     echo '<h3>', $movievalue['name'], '</h3>';
                     echo '<p>', $movievalue['description'], '</p>';

                     echo '</div>';

                    echo '</div>';

                echo '</li>';
            }

            echo '</ul>';
            echo '</div>';
        }
        echo '</div>';
        echo '<pre>', print_r($user_graph), '</pre>';
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
