<?php
$servername ="sci-mysql";
$username = "coa123wuser";
$password = "grt64dkh!@2FD";
$dbname = "coa123wdb";

$size = trim($_GET["capacity"]);
$grade = trim($_GET["grade"]);
$date1 = trim($_GET["dateFrom"]);
$date2 = trim($_GET["dateTo"]);

$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

$sql="SELECT name, capacity,
weekend_price,
weekday_price,
licensed,
COUNT(booking_date) AS booked_dates,
cost
FROM venue 
INNER JOIN 
catering 
ON venue.venue_id = catering.venue_id
INNER JOIN venue_booking
ON venue.venue_id = venue_booking.venue_id
WHERE capacity >= '$size'
AND grade = '$grade'
GROUP BY name, capacity, weekend_price, weekday_price, licensed, cost
";

$result = mysqli_query($conn, $sql); 
$allDataArray = array();
while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
  $allDataArray[] = $row;
}

if($date1 != $date2){//check if the two dates recieved from the form are equal, if they are not then a range of dates must be checked
	
  $sql3 = "SELECT name, booking_date
  FROM venue INNER JOIN venue_booking
  ON venue.venue_id = venue_booking.venue_id
  WHERE booking_date BETWEEN '$date1' AND '$date2';";
  
  $getBookedDatesInRange = mysqli_query($conn, $sql3);//using the sql3 statement, get the names of each venue and the corresponding dates that have been booked between two dates(date1 and date2) for each venue
  $bookedDatesInRange = array();
  while ($row = mysqli_fetch_array($getBookedDatesInRange, MYSQLI_ASSOC)) {
    $bookedDatesInRange[] = $row;
  }

  function getDates($fromDate, $toDate){//function which returns an array of all dates in between to dates
    $DatesArray= [];
				
	$fromDate = strtotime($fromDate);
	$toDate = strtotime($toDate);
				 
	for ($currentDate = $fromDate; $currentDate <= $toDate; $currentDate += (86400)) {											
	  $date = date('Y-m-d', $currentDate);
	  $DatesArray[] = $date;
	}
	
	return $DatesArray;
	}
	
  $RequestedRange = array();//initialise the array that will store all the dates between date1 and date2 inclusive 
  $RequestedRange = getDates($date1,$date2);

  for($i = 0; $i<count($allDataArray); $i++){//iterate through all venues that match the criteria of the required grade and capacity
	$datesNotAvailable = array();//initialise array that will store the booked dates for a venue between date1 and date2
	for($j = 0; $j<count($bookedDatesInRange); $j++){//iterate through array of venue names and booked dates
	  if($allDataArray[$i]["name"] == $bookedDatesInRange[$j]["name"]){//compare venue names
	    $datesNotAvailable[] = $bookedDatesInRange[$j]["booking_date"];//add booked date to array
	  }
	}
		
	$availableDatesArray = array();
	$availableDatesArray = array_diff($RequestedRange,$datesNotAvailable);//store the dates that have not been booked between date1 and date2
		
	if(count($availableDatesArray) == 0){//check if all dates between date1 and date2 have been booked
	  unset($allDataArray[$i]);//remove venue from array
	}else{
	  $availableDates = "";
	  foreach($availableDatesArray as &$date){
	    $availableDates .= ",$date";//create string of available dates
	  }
	  $allDataArray[$i]["availableDates"] = $availableDates;//append the string of available dates to end of the json data for the venue
	}	
  }

  $allDataArray2 = array_values($allDataArray);

  echo json_encode($allDataArray2);

}else{//both date1 and date2 are equal so no need to set up range of dates
	
  $sql2 = "SELECT name FROM venue INNER JOIN venue_booking
  ON venue.venue_id = venue_booking.venue_id WHERE booking_date = '$date1';";

  $booked_venues = mysqli_query($conn, $sql2);
  $bookedArray = array();
  while ($row = mysqli_fetch_array($booked_venues, MYSQLI_ASSOC)) {
	$bookedArray[] = $row;
  }
	
  for($i = 0; $i<count($allDataArray); $i++){
    $venue = $allDataArray[$i];
	for($j = 0; $j<count($bookedArray); $j++){
	  $bookedVenue = $bookedArray[$j];
	  if($venue["name"] == $bookedVenue["name"]){
	    unset($allDataArray[$i]);//remove venues that are booked on date1/date2
	  }
    }
  }
	
  $allDataArray2 = array_values($allDataArray);

  echo json_encode($allDataArray2);
	
}
?>