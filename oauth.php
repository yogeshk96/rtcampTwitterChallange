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
				//for 10 tweets to show the slider by default
				$twitterData = $connection->get('https://api.twitter.com/1.1/statuses/home_timeline.json?count=10');    
                
                //getting the total number of tweets and first 200 tweets
                $twitterData2 = $connection->get('https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name='.$content->name.'&count=200'); 
                $y = 0;
                foreach ($twitterData2 as $key => $value) {
                	
                	$countTweet = $value->user->statuses_count;
                	$tid = $value->id;
                	$tweets[$y]['text'] = $value->text;
                	$tweets[$y]['created_at'] = $value->created_at;
                	$tweets[$y]['id'] = $value->id;
                	$y++;
                }

                
                $numPages = ceil($countTweet/200);
                
                //getting the remaining tweets based on max_id (loop repeats for the total number of pages)
                for($j=1;$j<$numPages;$j++) {

                  $twitterData2 = $connection->get('https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name='.$content->name.'&count=200&max_id='.$tid);
	                  $first = 0;
	                  foreach ($twitterData2 as $key => $value) {
	                  	if($first > 0) {
		                  $tid = $value->id;
		                  $tweets[$y]['text'] = $value->text;
		                  $tweets[$y]['created_at'] = $value->created_at;
                	      $tweets[$y]['id'] = $value->id;
		                  $y++;
		                }
		                $first++;
		              }
                }
                
          
				//first page of followers list
				$pages = $connection->get('https://api.twitter.com/1.1/followers/list.json?cursor=-1&screen_name='.$content->name.'&include_user_entities=false&count=200'); 
				$i = 0;
				$nextCursor = $pages->next_cursor;
				foreach ($pages as $key=>$single_follower) {
	   	   
	                foreach ($single_follower as $inkey=>$single_followers) {
	             	$myFollowers[$i]['name'] = $single_followers->name;
		            $myFollowers[$i]['screen_name'] = $single_followers->screen_name;
	             	$i++;

	                }
	            }
	            while($nextCursor > 0) {//loop till the next_cursor value is '0' that means till all the followers names are stored

					$pages2 = $connection->get('https://api.twitter.com/1.1/followers/list.json?cursor='.$nextCursor.'&screen_name='.$content->name.'&include_user_entities=false&count=200');
					$nextCursor = $pages2->next_cursor;
					foreach ($pages2 as $key=>$single_follower) {
		   	   
		                foreach ($single_follower as $inkey=>$single_followers) {
		             	$myFollowers[$i]['name'] = $single_followers->name;
		             	$myFollowers[$i]['screen_name'] = $single_followers->screen_name;
		             	$i++;

		                }
		            }
		        }

	            //setting the sessions              
				$_SESSION['name']=$content->name;
				$_SESSION['image']=$content->profile_image_url;
				$_SESSION['screen_name']=$content->screen_name;
				$_SESSION['twitter_t']=$twitterData;
				$_SESSION['all_follower']=$myFollowers;
				$_SESSION['allTweets']=$tweets;
				            
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