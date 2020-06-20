<?php
//http://bulk.openweathermap.org/sample/
//https://phppot.com/php/forecast-weather-using-openweathermap-with-php/
//echo "qqqqq";
$apiKey = "76418bccd017a8cd4493205b12343c4f";
$cityId = "1273294";
$searchCityValue = 'Delhi';
$googleApiUrl = "http://api.openweathermap.org/data/2.5/weather?id=" . $cityId . "&lang=en&units=metric&APPID=" . $apiKey;

if(isset($_POST['q'])!=''){
	$searchCityValue = $_POST['q'];    
    if(is_numeric($searchCityValue) == '1'){
        $searchZipVal = '1';
    }else{
        $searchZipVal = '0';
	}    
    if($searchZipVal == '0'){

        //$searchCityValue = $_POST['q'];
        //search by typing city in search box
        //api.openweathermap.org/data/2.5/weather?q=London
        $googleApiUrl = "http://api.openweathermap.org/data/2.5/weather?q=" . $searchCityValue . "&lang=en&units=metric&APPID=" . $apiKey;

        //die('ttt');
    }
    if($searchZipVal == '1'){

        $searchZip = $_POST['q'];
        $country   = 'us'; 
        //search by typing city in search box
        //http://api.openweathermap.org/data/2.5/forecast?zip=94040,US&appid=76418bccd017a8cd4493205b12343c4f
       $googleApiUrl = "http://api.openweathermap.org/data/2.5/forecast?zip=" . $searchZip . ",".$country."&lang=en&units=metric&APPID=" . $apiKey;

        //die('ttt');
    }
    if(isset($_POST['lat'])){

        $searchLat = $_POST['lat'];
        $searchLon = $_POST['lon'];
        //search weather forecast for 4 days (96 hours) 
        //pro.openweathermap.org/data/2.5/forecast/hourly?lat=35&lon=139
        $googleApiUrl = "http://api.openweathermap.org/data/2.5/forecast?lat=" . $searchLat . "&lon=" . $searchLon . "&lang=en&units=metric&APPID=" . $apiKey;

        //die('ttt');
    }

}
$ch = curl_init();

curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_URL, $googleApiUrl);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_VERBOSE, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$response = curl_exec($ch);

curl_close($ch);
$data = json_decode($response);

//echo "<pre>";
//print_r($data);
//echo "</pre>";
//die('end');
$currentTime = time();
$chkData = $data->cod;
if($chkData =='404'){
	$showGrid = '0';
}else{
	$showGrid = '1';
}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0,maximum-scale=1">
		
		<title>Compass Starter by Ariona, Rian</title>

		<!-- Loading third party fonts -->
		<link href="http://fonts.googleapis.com/css?family=Roboto:300,400,700|" rel="stylesheet" type="text/css">
		<link href="fonts/font-awesome.min.css" rel="stylesheet" type="text/css">

		<!-- Loading main css file -->
		<link rel="stylesheet" href="style.css">
		
		<!--[if lt IE 9]>
		<script src="js/ie-support/html5.js"></script>
		<script src="js/ie-support/respond.js"></script>
		<![endif]-->

	</head>


	<body>
		
		<div class="site-content">
			<div class="site-header">
				<div class="container">
					<a href="index.php" class="branding">
						<img src="images/logo.png" alt="" class="logo">
						<div class="logo-type">
							<h1 class="site-title">Weather Forecast</h1>
							<small class="site-description">Check Weather</small>
						</div>
					</a>

					<!-- Default snippet for navigation -->
					<div class="main-navigation">
						<button type="button" class="menu-toggle"><i class="fa fa-bars"></i></button>
						<ul class="menu">
							<li class="menu-item current-menu-item"><a href="index.php">Home</a></li>
							<li class="menu-item"><a href="news.html">News</a></li>
							<li class="menu-item"><a href="live-cameras.html">Live cameras</a></li>
							<li class="menu-item"><a href="photos.html">Photos</a></li>
							<li class="menu-item"><a href="contact.html">Contact</a></li>
						</ul> <!-- .menu -->
					</div> <!-- .main-navigation -->

					<div class="mobile-navigation"></div>

				</div>
			</div> <!-- .site-header -->

			<div class="hero" data-bg-image="images/banner.png">
				<div class="container">
					<form id="nav-search-form" action="index.php" method="post" class="find-location">
						<input type="text" id="q" name="q" placeholder="Find your location...">
						<input type="submit" value="Find">
					</form>
				</div>
			</div>
			<div class="forecast-table">
				<div class="container">
					<div class="forecast-container">
						<?php if($showGrid == '1') { ?>
						<div class="today forecast">
							<div class="forecast-header">
								<div class="day"><?php echo date("l g:i a", $currentTime); ?></div>
								<div class="date"><?php echo date("jS F, Y",$currentTime); ?></div>
							</div> <!-- .forecast-header -->
							
							<div class="forecast-content">
								<div class="location">
								<?php 
									if($searchZipVal == '1'){
										echo $data->city->name;
										
									}else{
										echo $data->name;
									} 
								?>
								</div>
								<div class="degree">
									<div class="num">
									
									<?php 
										if($searchZipVal == '1'){
											echo round($data->list[0]->main->temp);
										}else{
											echo round($data->main->temp_max); 
										}
									
									?><sup>o</sup>C</div>
									<div class="forecast-icon">
										<?php
										if($searchZipVal == '1'){ ?>
										<img src="http://openweathermap.org/img/w/<?php echo $data->list[0]->weather->icon; ?>.png" alt="" width=90>
										<?php }else { ?> 
										<img src="http://openweathermap.org/img/w/<?php echo $data->weather[0]->icon; ?>.png" alt="" width=90>
										
										<?php } ?>
										</div>	
								</div>
								<span><img src="images/icon-umberella.png" alt="">
								<?php 
										if($searchZipVal == '1'){
											echo $data->list[0]->main->humidity;
										}else{
											echo $data->main->humidity;
										}
											

								?>%</span>
								<span><img src="images/icon-wind.png" alt="">
								<?php 
									if($searchZipVal == '1'){
										echo $data->list[0]->wind->speed;
									}else{
										echo $data->wind->speed; 
									}
									
								?>km/h</span>
							</div>
						</div>
						<?PHP 
						if(($data->list)!=''){
                    foreach($data->list as $day => $value) { ?>
						<div class="forecast">
							<div class="forecast-header">
								<div class="day">Day - <?php //echo $value->dt_txt; ?><?php echo $day; ?></div>
							</div> <!-- .forecast-header -->
							<div class="forecast-content">
								<div class="forecast-icon">
								
								<img src="http://openweathermap.org/img/w/<?php echo $value->weather->icon; ?>.png" alt="" width=48>
								<!-- <img src="images/icons/icon-3.svg" alt="" width=48> -->
								
							</div>
								<div class="degree"><?php echo round($value->main->temp_max); ?><sup>o</sup>C</div>
								<small><?php echo $value->main->temp_min; ?><sup>o</sup></small>C
							</div>
						</div>
						<?PHP 
						//echo "Max temperature for day " . $day . " will be " . $value->main->temp . "<br />" ;
						if($day == '7'){
                            exit;
                        }
                    } 
                } ?>
						

					<?php } else { ?>
									No record found.
							<?php } ?>
					</div>
				</div>
			</div>
			<main class="main-content">
				<div class="fullwidth-block">
					<div class="container">
						<h2 class="section-title">Live cameras</h2>
						<div class="row">
							<div class="col-md-3 col-sm-6">
								<div class="live-camera">
									<figure class="live-camera-cover"><img src="http://localhost:2100/weather/images/live-camera-1.jpg" alt=""></figure>
									<h3 class="location">New York</h3>
									<small class="date">8 oct, 8:00AM</small>
								</div>
							</div>
							<div class="col-md-3 col-sm-6">
								<div class="live-camera">
									<figure class="live-camera-cover"><img src="images/live-camera-2.jpg" alt=""></figure>
									<h3 class="location">Los Angeles</h3>
									<small class="date">8 oct, 8:00AM</small>
								</div>
							</div>
							<div class="col-md-3 col-sm-6">
								<div class="live-camera">
									<figure class="live-camera-cover"><img src="images/live-camera-3.jpg" alt=""></figure>
									<h3 class="location">Chicago</h3>
									<small class="date">8 oct, 8:00AM</small>
								</div>
							</div>
							<div class="col-md-3 col-sm-6">
								<div class="live-camera">
									<figure class="live-camera-cover"><img src="images/live-camera-4.jpg" alt=""></figure>
									<h3 class="location">London</h3>
									<small class="date">8 oct, 8:00AM</small>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="fullwidth-block" data-bg-color="#262936">
					<div class="container">
						<div class="row">
							<div class="col-md-4">
								<div class="news">
									<div class="date">06.10</div>
									<h3><a href="#">Doloremque laudantium totam sequi </a></h3>
									<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Illo saepe assumenda dolorem modi, expedita voluptatum ducimus necessitatibus. Asperiores quod reprehenderit necessitatibus harum, mollitia, odit et consequatur maxime nisi amet doloremque.</p>
								</div>
							</div>
							<div class="col-md-4">
								<div class="news">
									<div class="date">06.10</div>
									<h3><a href="#">Doloremque laudantium totam sequi </a></h3>
									<p>Nobis architecto consequatur ab, ea, eum autem aperiam accusantium placeat vitae facere explicabo temporibus minus distinctio cum optio quis, dignissimos eius aspernatur fuga. Praesentium totam, corrupti beatae amet expedita veritatis.</p>
								</div>
							</div>
							<div class="col-md-4">
								<div class="news">
									<div class="date">06.10</div>
									<h3><a href="#">Doloremque laudantium totam sequi </a></h3>
									<p>Enim impedit officiis placeat qui recusandae doloremque possimus, iusto blanditiis, quam optio delectus maiores. Possimus rerum, velit cum natus eos. Cumque pariatur beatae asperiores, esse libero quas ad dolorem. Voluptates.</p>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="fullwidth-block">
					<div class="container">
						<div class="row">
							<div class="col-md-4">
								<h2 class="section-title">Application features</h2>
								<ul class="arrow-feature">
									<li>
										<h3>Natus error sit voluptatem accusantium</h3>
										<p>Doloremque laudantium totam rem aperiam Inventore veritatis et quasi architecto beatae vitae.</p>
									</li>
									<li>
										<h3>Natus error sit voluptatem accusantium</h3>
										<p>Doloremque laudantium totam rem aperiam Inventore veritatis et quasi architecto beatae vitae.</p>
									</li>
									<li>
										<h3>Natus error sit voluptatem accusantium</h3>
										<p>Doloremque laudantium totam rem aperiam Inventore veritatis et quasi architecto beatae vitae.</p>
									</li>
								</ul>
							</div>
							<div class="col-md-4">
								<h2 class="section-title">Weather analyssis</h2>
								<ul class="arrow-list">
									<li><a href="#">Accusantium doloremque laudantium rem aperiam</a></li>
									<li><a href="#">Eaque ipsa quae ab illo inventore veritatis quasi</a></li>
									<li><a href="#">Architecto beatae vitae dicta sunt explicabo</a></li>
									<li><a href="#">Nemo enim ipsam voluptatem quia voluptas</a></li>
									<li><a href="#">Aspernatur aut odit aut fugit, sed quia consequuntur</a></li>
									<li><a href="#">Magni dolores eos qui ratione voluptatem sequi</a></li>
									<li><a href="#">Neque porro quisquam est qui dolorem ipsum quia</a></li>
								</ul>
							</div>
							<div class="col-md-4">
								<h2 class="section-title">Awesome Photos</h2>
								<div class="photo-grid">
									<a href="#"><img src="images/thumb-1.jpg" alt="#"></a>
									<a href="#"><img src="images/thumb-2.jpg" alt="#"></a>
									<a href="#"><img src="images/thumb-3.jpg" alt="#"></a>
									<a href="#"><img src="images/thumb-4.jpg" alt="#"></a>
									<a href="#"><img src="images/thumb-5.jpg" alt="#"></a>
									<a href="#"><img src="images/thumb-6.jpg" alt="#"></a>
									<a href="#"><img src="images/thumb-7.jpg" alt="#"></a>
									<a href="#"><img src="images/thumb-8.jpg" alt="#"></a>
									<a href="#"><img src="images/thumb-9.jpg" alt="#"></a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</main> <!-- .main-content -->

			<footer class="site-footer">
				<div class="container">
					<div class="row">
						<div class="col-md-8">
							<form action="#" class="subscribe-form">
								<input type="text" placeholder="Enter your email to subscribe...">
								<input type="submit" value="Subscribe">
							</form>
						</div>
						<div class="col-md-3 col-md-offset-1">
							<div class="social-links">
								<a href="#"><i class="fa fa-facebook"></i></a>
								<a href="#"><i class="fa fa-twitter"></i></a>
								<a href="#"><i class="fa fa-google-plus"></i></a>
								<a href="#"><i class="fa fa-pinterest"></i></a>
							</div>
						</div>
					</div>

					<p class="colophon">Copyright 2014 Company name. Designed by Themezy. All rights reserved</p>
				</div>
			</footer> <!-- .site-footer -->
		</div>
		
		<script src="js/jquery-1.11.1.min.js"></script>
		<script src="js/plugins.js"></script>
		<script src="js/app.js"></script>
		
	</body>

</html>