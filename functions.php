<?php

    function set_session($string) {

     	$_SESSION['list'] = $string;
    }

    function get_recentTweets($recent_tweets) {

     	foreach($recent_tweets AS $single_tweet){
	        $tweet = $single_tweet->text;
	        $name = $single_tweet->user->name;
	        $user_name = $single_tweet->user->screen_name;
	        $creation_time = $single_tweet->created_at;
	        $tweet = preg_replace("/([\w]+\:\/\/[\w-?&;#~=\.\/\@]+[\w\/])/", "<a target=\"_blank\" href=\"$1\">$1</a>", $tweet);
	        $tweet = preg_replace("/#([A-Za-z0-9\/\.]*)/", "<a target=\"_new\" href=\"http://twitter.com/search?q=$1\">#$1</a>", $tweet);
	        $tweet = preg_replace("/@([A-Za-z0-9\/\.]*)/", "<a href=\"http://www.twitter.com/$1\">@$1</a>", $tweet);
	        $html = $html. "<li class='tList'><strong>".$name." @ ".$user_name."</strong><br>".$tweet."<br>".$creation_time."</li>"; //forming html for the list of tweets
        }
        return $html;
     }

     function get_myTenFollowers($followers) {
        $i=1;
     	foreach ($followers as $key=>$single_followers) {

	   	   foreach ($single_followers as $inkey=>$single_follower) {
		      	if($i <= 10) {
		      		if($inkey == "screen_name")
		         	$screen_name = $single_follower;
		            if($inkey == "name")
		         	$name = $single_follower;
	            
		        }
	        }
		    if($i <= 10){
		    	$myFollowers[$screen_name] = $name;
		    	$i++;

		    }
	       
	    }
	    return $myFollowers;
     }

     function get_myAllFollowers($followers) {

     	foreach ($followers as $key=>$single_followers) {
	   	   
	      foreach ($single_followers as $inkey=>$single_follower) {
	      	
	      	    if($inkey == "screen_name")
	         	$screen_name = $single_follower;
	            if($inkey == "name")
	         	$name = $single_follower;

	       }

	       $myFollowers[$screen_name] = $name;
	    }
	    return $myFollowers;
     }

?>