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
$min = intval($_GET["minCapacity"]); //Get user input from form and convert string value to integer value
$max = intval($_GET["maxCapacity"]);

if(($max > $min) AND (is_numeric($max) AND is_numeric($min)) AND ($max > 0 AND $min > 0)){

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

$sql = "SELECT venue.name, venue.capacity, venue.weekend_price, venue.weekday_price, catering.cost 
		FROM venue INNER JOIN catering
		ON venue.venue_id = catering.venue_id
		WHERE capacity >= '$min' 
		AND capacity <= '$max'
		AND licensed = '1'";
		
$result = mysqli_query($conn, $sql);

echo "requested minimum capacity: ".$min;
echo "<br>";
echo "requested maximum capacity: ".$max;
echo "<br>";
echo "<hr>";

//Table heading
echo "<h1>A table representing the licensed venue names, venue prices and the capacity of each venue</h1>";

echo "<table>";
echo "<tr>";

echo "<td style='font-weight:bold'>".'Venue Name'."</td>"; 
echo "<td style='font-weight:bold'>".'Capacity'."</td>";
echo "<td style='font-weight:bold'>".'Weekend Price (£)'."</td>";
echo "<td style='font-weight:bold'>".'Weekday Price (£)'."</td>";
echo "<td style='font-weight:bold'>".'Catering cost per person (£)'."</td>";
echo "</tr>";

if (mysqli_num_rows($result) > 0){
	// output data of each row
	while ($row = mysqli_fetch_array($result)){
		if($row[1] >= $min && $row[1] <= $max){
		echo "<tr>";
		echo "<td>".$row[0]."</td>";
		echo "<td>".$row[1]."</td>";
		echo "<td>".$row[2]."</td>";
		echo "<td>".$row[3]."</td>";
		echo "<td>".$row[4]."</td>";
		echo "</tr>";
	}
	}
}else{
	echo "<p class='error'>Sorry there are no results for your search, please try change the minimum and maximum capacity</p>";
}

echo "</table>";

mysqli_close($conn);

}else{
	if($max < 0 OR $min < 0){
		echo "<p class='error'>Error: the minCapacity and maxCapacity must be posistive values, you have entered a negative value.</p>";
	}else if($max < $min){
		echo "<p class='error'>Error: maxCapacity (".$max.") must be larger than minCapacity(".$min.").</p>";
	}else{
		echo "<p class='error'>Error: a non integer value has been entered, please only enter whole numbers.</p>";
	}
}
?>
  </body>
</html>