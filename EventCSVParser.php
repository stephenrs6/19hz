<?php

    while (($data = fgetcsv($handle, 2048, ",")) !== FALSE) {
        $num = count($data);
        $row++;
		
		//compare the event date with the current time minus XXXX seconds (86400 seconds = 1 day)
		$checkTime = time() - 86400;
		
		//handle next year events
		//if (substr($data[0], -4) == "2019") {
		//	goto SkipTimeCheck;
		//}
		//if the event date 1pm is older than the current time minus 1 day, and also
		//the event is not a multi-day event, skip the line
		//use this section after new year
		if ((strtotime(substr($data[0], 5,6) . " 13:00" ) < $checkTime && ! strpos($data[0], "-",1))) {
			goto SkipEventShow;
		}

SkipTimeCheck:
		//use this section close to the end of a calendar year
		//if (substr($data[0], -4) !== "2018") {
			//if ((strtotime(substr($data[0], 5,6) . " 13:00" ) < $checkTime && ! strpos($data[0], "-",1))) {
				//goto SkipEventShow;
			//}		
		//}

		echo "<tr>";
		//handles events parsing//
		for ($c=0; $c < $num; $c++) {
			if ($c < 8) {
					//create the Date/Time column
				if ($c == 0) {
					if (empty($data[4]) === false) {
						echo "<td>" . $data[$c] . "<br />(" . $data[4] . ")</td>";
					} else {
						echo "<td>" . $data[$c] . "</td>";
					}
					//create the Event Title @ Venue column
				} elseif ($c == 1) {
					echo "<td><a href='" . $data[8] . "'>" . utf8_encode($data[1]) . "</a> @ " . utf8_encode($data[3]);
					//do nothing for these columns
				} elseif (($c == 3) || ($c == 4) || ($c == 6)) {
					//create the Price|Age column
				} elseif ($c == 5) {
					if (empty($data[5]) === true) {
						//if there is no price but an age
						if (empty($data[6]) === false) {
							echo "<td>" . $data[6] . "</td>";
						}
						else {
							//they are both blank
							echo "<td></td>";
						}
					} else {
					//there is a price but no age
						if (empty($data[6]) === true) {
							echo "<td>" . $data[5] . "</td>";
						} else {
							//there are both price and age
							echo "<td>" . $data[5] . " | " . $data[6] . "</td>";
						}
					}
				}
					//creates the Tags and Organizers columns
				else {
					echo "<td>" . utf8_encode($data[$c]) . "</td>";					
				}

			//column 8 and higher, handles the last visible column, Links
			} else {
				$linkCell = "<td>";
				while (($c+1) <= $num) { //'$c holds the column of the current column being examined in the row - this loop will run at least once

					//assumes the 9th column will hold a secondary link
					if ($c == 9) {
						if ($data[$c] != '') {
							//parse out the URLs, assuming they start with http
							$splitString = explode(" ",$data[$c]);
							for($i = 0; $i < count($splitString); $i++) {
								if (substr($splitString[$i],0,4) === "http") {
									$linkString = $splitString[$i];
									break;
								}
							}
							//specifically parse out facebook links. If second link is not FB
							//use different text than "Facebook Page"
							$findString = strpos($linkString, "facebook");

							if ($findString === false) {
								$linkCell = $linkCell . "<a href='" . $linkString . "'>Event Link</a><br />";
							} else {	
								$linkCell = $linkCell . "<a href='" . $linkString . "'>Facebook Page</a><br />";
							}
						}	//end check if string is empty
					}  //end if column 9
					$c++;   //go to the next column in the CSV
				}	//end while loop
				$linkCell = $linkCell . "</td>";
				echo $linkCell;

				//assume the 10th column will be the sortable date
				$n = ($data[10]-25569) * 86400;
				echo "<td><div class='shrink'>" . gmdate("Y/m/d", $n) . "</div></td>";

			}	//end link check or "if $c < 8 statement"
		}	//end field scan per row for loop

//		echo "</div>";		//end of row-shading CSS
		echo "</tr>";

SkipEventShow:		

    }	//end file read loop

?>

