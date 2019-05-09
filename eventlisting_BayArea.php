<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head profile="http://www.w3.org/2005/10/profile">
<link rel="icon" type="image/png" href="favicon.png">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
    <?php include('header.php'); ?>
	<?php $location='BayArea'; include('tracker.php'); ?>   
	<title>San Francisco Bay Area/Northern California Electronic Music - Event Listing</title>
</head>

<body>
<!-- dark mode script -->
	<script type="text/javascript" charset="utf8" src="darkmode.js"></script> 

<!-- header -->    
    <div id="expand-box">
        <div id="expand-box-header">
            <span style="float: left;">
                <h2>San Francisco Bay Area / Northern California</h2>	<!-- Page title -->
                <a href="http://19hz.info">Home page</a> | <a href="http://19hz.info/addevent.php"><strong>Add an event</strong></a>
                <br /><br />
                <a href='#venueList'>Venues List</a> | <a href='#crewList'>Promoter List</a> | <a href='pastEvents_BayArea.php'>Past Events</a> | <a href='#recurring'>Recurring</a>
                <br /><br />
                Comments/corrections: <a href='mailto:19hzinfo@gmail.com'>19hzinfo@gmail.com</a>
                <br /><br />            
                <!-- Page filter -->
                Filter (by location): 
                <button onclick="locationfilter('San Francisco')">San Francisco</button> 
                <button onclick="locationfilter('Oakland')">Oakland</button> 
                <button onclick="locationfilter('San Jose')">San Jose</button>
                <button onclick="locationfilter('Sacramento')">Sacramento</button>    
                <button onclick="locationfilter('')">Clear Filter</button>
                <br /><br />
                Search (<a href="howtosearch.html">help</a>): <input type="search" id="filter" onkeyup="filterTable()" placeholder="">
            </span>  <!-- end left align span -->
    
            <span class="rightfloat">  <!-- use CSS to right align only on screen.width >640 -->
                <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
                <input type="hidden" name="cmd" value="_s-xclick">
                <input type="hidden" name="hosted_button_id" value="2M3VZZ5TRHXDJ">
    
                <!-- use a different image based on screen width -->
                <script language="javascript">
                    if ((screen.width) > 640) {
                        document.write('<input type="image" src="https://19hz.info/images/support_button_v2.jpg" border="0" bordername="submit" alt="PayPal Tip Link">');
                    } else {
                        document.write('<input type="image" src="https://19hz.info/images/support_button_v2_small.jpg" border="0" bordername="submit" alt="PayPal Tip Link">');	
                    }
                </script>
                <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
                </form>
            </span>  <!-- end right align span -->
            
        </div>    <!--end div header row -->
    </div>  <!-- end div for align left/right-->

<!-- Upcoming events -->
    <table>
        <thead><tr>
            <th class="table-date">Date/Time</th>
            <th>Event Title @ Venue</th>
            <th>Tags</th>
            <th>Price | Age</th>
            <th>Organizers</th>
            <th>Links</th>
            <th></th>
        </tr></thead>
	    <tbody>
			<?php
                $row = 1;
                if (($handle = fopen("events_" . $location . ".csv", "r")) !== FALSE) {
	                include 'EventCSVParser.php';
	                fclose($handle);
            	}
			?>
        </tbody>
    </table>
  
<!-- Recurring events -->
<h3 id='recurring'>Recurring</h3>
	<table>
        <thead><tr>
            <th class="table-date">Date/Time</th>
            <th>Event Title @ Venue</th>
            <th>Tags</th>
            <th>Price | Age</th>
            <th>Organizers</th>
            <th>Links</th>
        </tr></thead>
        <tbody>
			<?php
	        $row = 1;
	        if (($handle = fopen("events_" . $location . "_Recurring.csv", "r")) !== FALSE) {
		        include 'EventCSVRecurringParser.php';
		        fclose($handle);
        	}?>
		</tbody>
    </table>

<!--section for venues and crews-->
<h3 id='venueList'>Venue List</h3>
    <table>
        <thead><tr>
            <th>Venue Name</th>
            <th>Physical Address</th>
            <th>Additional Links</th>
        </tr></thead>
	    <tbody>
			<?php
			if (($handle = fopen("venues_" . $location . ".csv", "r")) !== FALSE) {
				$row = 1;
				while (($data = fgetcsv($handle, 2048, ",")) !== FALSE) {
					$num = count($data);
					echo "<tr>";
					$row++;

					//parse venues by column//
					for ($c=0; $c < $num; $c++) {
			
						//column 3 is where the URL's start
						if ($c < 2) {
							if ($c == 0) {
								echo "<td><a href='" . $data[2] . "'>" . $data[0] . "</a>";
							}
							else {
								echo "<td>" . $data[$c] . "</td>";   				 
							}
						}
			
						else {
							$linkCell = "<td>";
							while (($c+1) <= $num) { //'$c holds the column of the current column being examined in the row - this loop will run at least once
			
							if ($c == 3) {
								$splitString = explode(" ",$data[$c]);
								for($i = 0; $i < count($splitString); $i++) {
									if (substr($splitString[$i],0,4) === "http") {
										$linkString = $splitString[$i];
										break;
									}
								}
								$linkCell = $linkCell . "<a href='" . $linkString . "'>" . $data[$c] . "</a><br />";
								}
								$c++;
							}
							$linkCell = $linkCell . "</td>";
							echo $linkCell;
						}    //end link check or "if $c < 2 statement"    
					}    //end field scan per row for loop
					echo "</div></tr>";
					}    //end file read loop
				fclose($handle);
			}    //end check if file can be opened
    		?>
        </tbody>
    </table>

<h3 id="crewList">Promoter List</h3>
	<?php
	if (($handle = fopen("crews_" . $location . ".csv", "r")) !== FALSE) {
    	$row = 1;
	    while (($data = fgetcsv($handle, 2048, ",")) !== FALSE) {
    		$num = count($data);
    		$row++;
			echo "<tr>";

			//handles crew parsing//
			for ($c=0; $c < $num; $c++) {
				//column 2 is where the URL's start
				if ($c < 1) {
					if ($data[$c] == "x") {
						echo "<br />";
						echo "<strong>Possibly inactive</strong>";
						echo "<br />";
						echo "<br />";
					}
					elseif ($c == 0) {
						echo "<a href='" . $data[1] . "'>" . $data[0] . "</a>";
						echo "<br />";
					}
					else {
						echo $data[$c];   				 
						echo "<br />";
					}
				}
				else {
				}    //end link check or "if $c < 1 statement"    
			}    //end field scan per row for loop
		}    //end file read loop
		fclose($handle);
	}    //end check if file can be opened
	?>
  
	<p>Most updates happen on Tuesdays or Wednesdays.</p>

<!-- Code for filtering -->
	<script type="text/javascript" charset="utf8" src="table-filter-v2.js"></script>
</body>
</html>

