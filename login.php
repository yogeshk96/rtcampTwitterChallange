<?php 
session_start();
require_once('lib/twitteroauth/twitteroauth.php');
include('config.php');
include('functions.php');
?>
<html>
<!-- start: HEAD -->
	<head>
		<title>Twitter-Timeline Challenge</title>
		<!-- start: META -->
		<meta charset="utf-8" />
		<!--[if IE]><meta http-equiv='X-UA-Compatible' content="IE=edge,IE=9,IE=8,chrome=1" /><![endif]-->
		<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<!-- end: META -->
		<!-- start: MAIN CSS -->
		<link rel="stylesheet" href="lib/css/bootstrap.min.css">
		<link rel="stylesheet" href="lib/css/font-awesome.min.css">
		<link rel="stylesheet" href="lib/css/main.css">
		<link rel="stylesheet" href="lib/css/main-responsive.css">
		<!--[if IE 7]>
		<link rel="stylesheet" href="lib/plugins/font-awesome/css/font-awesome-ie7.min.css">
		<![endif]-->
		<!-- end: MAIN CSS -->
		<!--Our css file-->
		<link rel='stylesheet' href='style.css'>
        <!--end: Our css file-->
		<link rel="shortcut icon" href="#" />
	</head>
<body>
	<div class="main-container">

		<!-- start: Top menu -->
		<div class="navbar navbar-inverse navbar-fixed-top">
			<!-- start: TOP NAVIGATION CONTAINER -->
			<div class="container" style="background:#3475ba;">
				<div class="navbar-header">
					<!-- start: RESPONSIVE MENU TOGGLER -->
					<button data-target=".navbar-collapse" data-toggle="collapse" class="navbar-toggle" type="button">
						<span class="clip-list-2"></span>
					</button>
					<!-- end: RESPONSIVE MENU TOGGLER -->
					<!-- start: LOGO -->
						<img src="https://rtcamp.com/wp-content/uploads/2013/11/logo1.png">
					<!-- end: LOGO -->
				</div>
				<div class="navbar-tools">
					<!-- start: TOP NAVIGATION MENU -->
					<ul class="nav navbar-right">
						<!-- start: USER DROPDOWN -->
						<li class="dropdown current-user">
							<a data-toggle="dropdown" data-hover="dropdown" class="dropdown-toggle" data-close-others="true" href="#">
								<img src="<?php echo $_SESSION['image']; ?>" class="circle-img" alt="">
								<span class="username" style="color:#000;"><?php echo $_SESSION['name']; ?></span>
								<i class="clip-chevron-down"></i>
							</a>
							<ul class="dropdown-menu">
								<li>
									<a href="logout.php">
										<i class="clip-exit"></i>
										&nbsp;Log Out
									</a>
								</li>
							</ul>
						</li>
						<!-- end: USER DROPDOWN -->
					</ul>
					<!-- end: TOP NAVIGATION MENU -->
				</div>
			</div>
			<!-- end: TOP NAVIGATION CONTAINER -->
		</div>
		<!-- end: HEADER -->
		<!-- start: PAGE -->
		<?php if(isset($_SESSION['name']) && isset($_SESSION['screen_name'])) //check whether user already logged in with twitter
        { 
        	$recent_tweets = $_SESSION['twitter_t'];
            $allFollowers = $_SESSION['all_follower'];
            
            $allTweets = json_encode($_SESSION['allTweets']);

            
        ?>
		<div class="main">
			<div class="container" id="container">
				
				<!-- end: PAGE HEADER -->
				<!-- start: PAGE CONTENT -->
				<div class="row">

					<div class="col-md-4">
					</div>
					<div class="col-md-4">
					    <h3 style="text-align:center;">Your Timeline Latest Tweets<?php echo ""; ?></h3>
						<div id='slider'>
						     <a href='#' class='control_next'>>></a>
						     <a href='#' class='control_prev'><</a>
						     <ul id='tweetList'>
						      <?php echo get_recentTweets($recent_tweets); ?>
						     </ul>  
		                </div>
					    <!-- start: FOLLOWER LIST BOX -->
						<div id='followers-list'>
						 <h3>Seach for your followers</h3>
						 <input type='text' name='searchTerm' id='searchTerm' />
						 <h3>Your followers list</h3>
							 <div id='followers-listInner'>
								 <table class="table table-hover" id="sample-table-1">
									 <tbody>
										 <?php 

										 //getting the list of 10 followers to show on the fron box
										 $myFollowers = get_myTenFollowers($allFollowers);

										 foreach ($myFollowers as $key=>$value) { ?>
										 	
										 	<tr class='fList'><td class='fListInner'><?php echo $value; ?><div class="fListHidden" style="display:none;"><?php echo $key; ?></div></td></tr>
										 <?php } ?>
									 </tbody>
								 </table>
								 <?php
								 //getting the list of all followers
								 $myAllFollowers = get_myAllFollowers($allFollowers);
								 $myFollowJson = json_encode($myAllFollowers);

			                     //setting session to call back the array in action page
			                     set_session($myFollowJson); 

                                 //creating the json file on server so that user can download it
                                 if (!file_exists('files')) {
		                            mkdir('files', 0775);
	                             } 
			                     $the_file_html = fopen("files/".$_SESSION['screen_name'].".json", "w") or die("Cannot open file for cache");
								 fwrite($the_file_html, $allTweets);
								 fclose($the_file_html);

								 ?>
						  
						     </div>
						 </div>
						 <!-- end: FOLLOWER LIST BOX -->
					 </div>
					<div class="col-md-4" style="margin-top:100px;">
							<h4>Download all your tweets in 'json' format</h4>
							<a class="btn btn-default" id = "downloadTweets" href="files/<?php echo $_SESSION['screen_name'].".json"; ?>" download="<?php echo $_SESSION['screen_name']."Tweets.json"; ?>">Download Tweets</a>	
					</div>
										

				</div>

			</div>
		</div>

	<?php }
	else // Not logged in
	{

			$connection = new TwitterOAuth($CONSUMER_KEY, $CONSUMER_SECRET);
			$request_token = $connection->getRequestToken($OAUTH_CALLBACK); //get Request Token

			if(	$request_token)
			{
				$token = $request_token['oauth_token'];
				$_SESSION['request_token'] = $token ;
				$_SESSION['request_token_secret'] = $request_token['oauth_token_secret'];
				
				switch ($connection->http_code) 
				{
					case 200:
						$url = $connection->getAuthorizeURL($token);
						//redirect to Twitter .
				    	header('Location: ' . $url); 
					    break;
					default:
					    echo "Coonection with twitter Failed";
				    	break;
				}

			}
			else //error receiving request token
			{
				echo "Error Receiving Request Token";
			}
			

	} ?>
		<!-- end: PAGE -->

	</div>
<!-- start: MAIN JAVASCRIPTS -->
		<!--[if lt IE 9]>
		<script src="lib/plugins/respond.min.js"></script>
		<script src="lib/plugins/excanvas.min.js"></script>
		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		<![endif]-->
		<!--[if gte IE 9]><!-->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
		<!--<![endif]-->
		<script src="lib/js/bootstrap.min.js"></script>
		<script src="lib/js/bootstrap-hover-dropdown.min.js"></script>
		<script type='text/javascript' src='script.js'></script>
		<!-- end: MAIN JAVASCRIPTS -->

</body>

</html>