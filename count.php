<!DOCTYPE html>
<html lang ="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width"/>
    <style>
    table,td,th{
	  border: 1px solid;
	  text-align: left;
	  padding: 15px;
    }

    table{
	  border-collapse: collapse;
	  width: 100%;
    }
	
    .error{
	  font-size: 40px;
	  color: red;
    }
    </style>
    <title>Database processing</title> 
  </head>

  <body>

  <?php
  $month = $_GET["month"]; //Get enetered month by user from form 

  if($month >= 1 && $month <= 12){ //Validate input from user

    //connect to database
    $servername ="sci-mysql";
    $username = "coa123wuser";
    $password = "grt64dkh!@2FD";
    $dbname = "coa123wdb";
    // Create connection
    $conn = mysqli_connect($servername, $username, $password, $dbname);
    // Check connection
    if (!$conn) {
	  die("Connection failed: " . mysqli_connect_error());
    }
    echo "Connected successfully";
    echo "<br>";

    $date; //variable to store date for SQL statement 

    if($month >= 1 && $month <=9){ //conditional check to see whether the month needs a 0 before month in date format
	  $date = "2022-0".$month;
    }else{
	  $date = "2022-".$month;
    }

    $sql = "SELECT name, COUNT(booking_date)
		    FROM venue INNER JOIN venue_booking
		    ON venue.venue_id = venue_booking.venue_id
		    WHERE booking_date LIKE '$date%'
		    GROUP BY name
		    ORDER BY COUNT(booking_date) DESC";
		
    $result = mysqli_query($conn, $sql);

	echo "requested month: ".$month;
	echo "<br>";
	echo "<hr>";

	//Table heading
	echo "<h1>A table representing the names of venues and the total number of bookings for a given month</h1>";

	echo "<table>";
	echo "<tr>";

	echo "<td style='font-weight:bold'>".'Venue Name'."</td>"; 
	echo "<td style='font-weight:bold'>".'total number of bookings in month '.$month."</td>";
	echo "</tr>";

	if (mysqli_num_rows($result) > 0){
	  // output data of each row
	  while ($row = mysqli_fetch_array($result)){
		echo "<tr>";
		echo "<td>".$row[0]."</td>";
		echo "<td>".$row[1]."</td>";
		echo "</tr>";
		}
	}

	echo "</table>";

	mysqli_close($conn);
  }else{
	echo "<p class='error'>ERROR: The month ".$month." is invalid. Please reselect a month between 1 and 12.</p>";
  }
  ?>
  </body>
</html>