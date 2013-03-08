<?php
/* 
   Created By Antony Magee @ antony.magee@gmail.com 
   -----------------------January 2013 ----------------------- 
   */
// Tells the page that this is RSS
header("Content-Type: application/rss+xml; charset=ISO-8859-1");

// captures the id of the user passed via the url
$hash = $_GET['h'];

include 'connect.php';

// attatches all the header info for the RSS feed to the rssfeed variable
$rssfeed = '<?xml version="1.0" encoding="ISO-8859-1"?>';
$rssfeed .= '<rss version="2.0">';
$rssfeed .= '<channel>';
$rssfeed .= '<title></title>';
$rssfeed .= '<link>http://www.iloveqc.org</link>';
$rssfeed .= '<description>Syndacated feed for twitter/instagram and twitter</description>';
$rssfeed .= '<language>en-us</language>';
$rssfeed .= '<copyright>Copyright (C) 2013 iloveqc.org</copyright>';

    // Grabs the approved tweets from the database that match the id of the user
    $result = mysql_query("SELECT * FROM storedtweets WHERE approved = 0 AND memid = '$hash'") or die ("Could not execute query");


    // runs them through a while loop to display each tweet
    while($row = mysql_fetch_array($result)) {
        extract($row);

        $img = strstr($row['img_url'], 'insta');
        $expInsta = explode("/", $img);
                    
        $twitpic = strstr($row['img_url'], 'twitpic');
        $expTwitpic = explode("/", $twitpic);
 
        $rssfeed .= '<item>';
        $rssfeed .= '<title>' . $user . '</title>';
        $rssfeed .= '<description>' . $text . '</description>';
        if ($img != FALSE) {
                            $rssfeed .= "<link>http://instagr.am/p/" .$expInsta[2]. "/media/?size=l</link>";
                          }   elseif ($twitpic != FALSE){
                            $rssfeed .= "<link>http://twitpic.com/show/full/" .$expTwitpic[1]. "</link>"; 
                          }   else {
                          }
        //$rssfeed .= '<pubDate>' . date("D, d M Y H:i:s O", strtotime($time_sent)) . '</pubDate>';
        $rssfeed .= '</item>';
    }
 
    $rssfeed .= '</channel>';
    $rssfeed .= '</rss>';

    // Echos the final rssfeed$ var that contains the header stuff and all the approved tweets
    echo $rssfeed;

?>