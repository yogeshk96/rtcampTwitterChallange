<?php
require_once('lib/twitteroauth/twitteroauth.php');
include('config.php');
$screen_name = $_POST['name'];
$connection = new TwitterOAuth($CONSUMER_KEY, $CONSUMER_SECRET);
$twitterData = $connection->get('https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name='.$screen_name.'&count=10');    

    foreach($twitterData AS $single_tweet){
        $tweet = $single_tweet->text;
        $name = $single_tweet->user->name;
        $user_name = $single_tweet->user->screen_name;
        $creation_time = $single_tweet->created_at;
        $tweet = preg_replace("/([\w]+\:\/\/[\w-?&;#~=\.\/\@]+[\w\/])/", "<a target=\"_blank\" href=\"$1\">$1</a>", $tweet);
        $tweet = preg_replace("/#([A-Za-z0-9\/\.]*)/", "<a target=\"_new\" href=\"http://twitter.com/search?q=$1\">#$1</a>", $tweet);
        $tweet = preg_replace("/@([A-Za-z0-9\/\.]*)/", "<a href=\"http://www.twitter.com/$1\">@$1</a>", $tweet);
        $html = $html. "<li class='tList'><strong>".$name." @ ".$user_name."</strong><br>".$tweet."<br>".$creation_time."</li>"; //forming html for the list of tweets
    }

    echo $html;

?>