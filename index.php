<?
	require 'php-sdk/facebook.php';
	
	// Create our Application instance (replace this with your appId and secret).
	$facebook = new Facebook(array(
	  'appId'  => '457681004272032',
	  'secret' => '3e3d797f61d5d598ff6a07782c47a6c5',
	));
	$user = $facebook->getUser();
    $qty = 5;
    $currOffset = $_GET['offset'];
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

        $friends_graph = $facebook->api(array(
        'method' => 'fql.query',
        'query' => "select uid from user where uid in (select uid2 from friend where uid1 = me()) and movies != ''"
        ));

        $offsetText = ($currOffset) ? "OFFSET $currOffset" : '';
        $movies_graph = $facebook->api(array(
        'method' => 'fql.query',
        'query' => "select name, uid from user where uid in (select uid2 from friend where uid1 = me()) and movies != '' LIMIT $qty $offsetText"
        ));


        echo '<div class="movegroupp">';
        foreach ($movies_graph as $key => $value) {
            echo '<div class="friend group">';
            echo '<div class="friendinfo group">';
            echo '<a href="http://facebook.com/', $value['uid'], '" target="_top">';
            echo '<img class="frienddumb" src="https://graph.facebook.com/',
                $value['uid'], '/picture" alt="',
                $value['name'], '"/>';
            echo '</a>';
            echo '<h2>', $value['name'], '</h2>';
            echo '<h3>Recommends</h3>';
            echo '</div>'; // friendinfo

            echo '<ul class="movies group">';
            $moviePath = '/'.$value['uid'].'/movies?fields=id,name,description,picture.width(100).height(100).type(square)';
            $movies_graph = $facebook->api($moviePath);

            foreach ($movies_graph['data'] as $moviekey => $movievalue) {
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

        } // each friend
        echo '</div>';  // movegroupp
        $numFriends = count($friends_graph);
        $totalPage = ceil($numFriends / $qty);
        $currePage = $currOffset / $qty + 1;
        $nextOffset = $currOffset + $qty;

        if ($totalPage > 1):
            echo '<div class="paging">';
            echo '<div class="pagenav">';

                if ($currOffset >= $qty) :
                   echo '<span class="previous">';
                   echo '<a href="',$_SERVER['SELF'],'?offset=',
                      $currOffset-$qty, '">&laquo; Previous</a>';
                   echo '</span>';
                endif;

                for ($i = 0; $i < $totalPage; $i++) {
                    echo '<span class="number';
                    if ($i == $currePage -1):
                        echo ' current ';
                    endif;

                    echo '">';
                    echo '<a href="', $_SERVER['SELF'],'?offset=', $qty*$i, '">', $i+1 ,'</a>';
                    echo '</span>';
                }

                if ($nextOffset < $numFriends) :
                   echo '<span class="next">';
                   echo '<a href="',$_SERVER['SELF'],'?offset=',
                      $nextOffset, '">Next &raquo;</a>';
                   echo '</span>';
                endif;

            echo '</div>';
            echo '</div>';
            echo '<div class="info">Page ', $currePage, ' of ', $totalPage, '</div>';
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
