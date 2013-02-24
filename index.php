<?
	require 'php-sdk/facebook.php';
	
	// Create our Application instance (replace this with your appId and secret).
	$facebook = new Facebook(array(
	  'appId'  => '457681004272032',
	  'secret' => '3e3d797f61d5d598ff6a07782c47a6c5',
	));
	$user = $facebook->getUser();
    $qty = 5;
    $currOffset = $_GET('offset');
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
        $moviePath='me/friends?fields=id,name,movies.fields(likes,id,name,created_time,picture.width(100).height(100).type(square),link,description)
        &limit='.$qty.'&offset='.$currOffset;
        $movies_graph = $facebook->api($moviePath);

        echo '<div class="movegroupp">';
        foreach ($movies_graph['data'] as $key => $value) {
            if (count($value['movies']['data'])) :
                echo '<div class="friend group">';
                echo '<div class="friendinfo group">';
                echo '<a href="http://facebook.com/', $value['id'], '" target="_top">';
                echo '<img class="frienddumb" src="https://graph.facebook.com/',
                    $value['id'], '/picture" alt="',
                    $value['name'], '"/>';
                echo '</a>';
                echo '<h2>', $value['name'], '</h2>';
                echo '<h3>Recommends</h3>';
                echo '</div>'; // friendinfo

                echo '<ul class="movies group">';

                foreach ($value['movies']['data'] as $moviekey => $movievalue) {
                    echo '<li>';
                    echo '<a href="', $movievalue['link'], '" target="_top">';
                    echo '<img class="moviethumb" src="', $movievalue['picture']['data']['url'],
                        '" alt="', $movievalue['name'], '" title="',
                        $movievalue['name'], '"/>';
                    echo '</a>';
                    echo '<div class="movieinfo">';
                    echo '<div class="wrapper">';
                    echo '<h3>', $movievalue['name'], '</h3>';
                    echo '<p>', $movievalue['description'], '</p>';
                    echo '</div>'; // wrapper
                    echo '</div>'; // movie info
                    echo '</li>';
                }

                echo '</ul>';
                echo '</div>'; // friend
            endif;

        } // each friend
        echo '</div>';  // movegroupp
        $numFriends = count($moviePath['data']);
        $totalPage = ceil($numFriends / $qty);

        if ($totalPage > 1):
            echo '<div class="pagination">';

            $currePage = $currOffset / $qty + 1;
            $nextOffset = $currOffset + $qty;
            echo '<div class="info">Page, $currePage, 'of ', $totalPage, '</div>';
            echo '</div>';
        endif;
    else:
        $loginUrl = $facebook->getLoginUrl(array(
        	'display'=>'popup',
        	'scope' => 'email,friends_likes',
        	'redirect_uri' => 'http://apps.facebook.com/coder-mulan/'
        ));
        echo '<p><a href="', $loginUrl, '" target="_top">login</a></p>';
    endif;
 ?>
 </BODY>
 </HTML>
