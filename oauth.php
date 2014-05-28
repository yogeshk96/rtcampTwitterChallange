<?php
session_start();
require_once('lib/twitteroauth/twitteroauth.php');
include('config.php');


if(isset($_GET['oauth_token']))
{
    

	$connection = new TwitterOAuth($CONSUMER_KEY, $CONSUMER_SECRET, $_SESSION['request_token'], $_SESSION['request_token_secret']);
	$access_token = $connection->getAccessToken($_REQUEST['oauth_verifier']);
	if($access_token)
	{
			$connection = new TwitterOAuth($CONSUMER_KEY, $CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);
			$params =array();
			$content = $connection->get('account/verify_credentials',$params);
			
			if($content && isset($content->screen_name) && isset($content->name))
			{
				$twitterData = $connection->get('https://api.twitter.com/1.1/statuses/home_timeline.json?count=10');    
				$followerData = $connection->get('https://api.twitter.com/1.1/followers/list.json?cursor=-1&screen_name='.$content->name.'&skip_status=true&include_user_entities=false&count=10'); 
                
				$count = 10;
				$_SESSION['name']=$content->name;
				$_SESSION['image']=$content->profile_image_url;
				$_SESSION['twitter_id']=$content->screen_name;
				$_SESSION['twitter_t']=$twitterData;
				$_SESSION['twitter_follower']=$followerData;
				            
				//redirect to main page.
				header('Location: login.php'); 

			}
			else
			{
				echo "<h4> Login Error </h4>";
			}
	}

else
{

	echo "<h4> Login Error </h4>";
}

}
else //Error. redirect to Login Page.
{
	header('Location: http://hayageek.com/examples/oauth/twitter/login.html'); 

}

?>