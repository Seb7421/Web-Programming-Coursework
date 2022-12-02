<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
	
	<title>Wedding Venue Finder Website</title>
	<link rel="stylesheet" href="wedding.css">
	<link rel="icon" type="image/JPG" href="Hilltop Mansion.jpg">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
	  function validateDates(date1, date2) {//function which compares two dates checking if one date is before another
	    return date1 <= date2;//returns true if date1 is before date2
	  }
		
	  $(document).ready(function () {
	    $("#venue-form").submit(function (event) {
		  event.preventDefault();
		  let date1 = $("#start-date").val();
		  let date2 = $("#end-date").val();
		  let partySize = $("#capacity").val();
		  let cateringGrade = $("#grade").val();
		  let insertedHtml = "<div id = 'venue-results'>";
			
		  if(validateDates(date1, date2)){//check if the start date is a later date than the end date
		    $.ajax({
			  url: "sendjson.php",
			  type: "GET",
			  data: {dateFrom:date1, dateTo:date2, capacity:partySize, grade:cateringGrade},
			  success: function (responseData) {
			    let len = responseData.length;
			    if(len == 0){ //Conditional check to return message notifying user that no results have come from their search
			      insertedHtml += "<h3> Hhmm... There seems to be no results for your search, Try: changing the catering grade or adjusting capacity</h3></div>";
			      $("#server-response").html(insertedHtml);
			    }else{
			      for (let i = 0; i < len; i++) {
				    let name = responseData[i].name;
				    let capacity = responseData[i].capacity;
								
				    let licensed; 
				    if(responseData[i].licensed == 1){ //give clearer value for user for determing whether the venue is licensed or not
				      licensed = "yes";
				    }else{
				      licensed = "no"
				    }
								
				    let cateringCost = responseData[i].cost;
				    let weekendPrice = responseData[i].weekend_price;
				    let weekdayPrice = responseData[i].weekday_price;
				    let bookings = responseData[i].booked_dates;
								
				    insertedHtml +=  "<div class = 'flexbox-container-results'>" +
				    "<div class = 'flexbox item1'>";
				    insertedHtml += "<h2>" + name + "</h2>";
				    insertedHtml += "<p> Venue capacity: " + capacity + "</p>";
				    insertedHtml += "<p> licensed: " + licensed + "</p>";
				    insertedHtml += "<p> Catering Cost (per person): £" + cateringCost + "</p>";
				    insertedHtml += "<p> Weekend Venue Hire Price: £" + weekendPrice + "</p>";
				    insertedHtml += "<p> Weekday Venue Hire Price: £" + weekdayPrice + "</p>";
				    insertedHtml += "<p> Number of past bookings: " + bookings + "</p>";
								
				    if(date1 != date2){//check if the two dates entered are the samee
					  let dates = (responseData[i].availableDates).substring(1);//remove first comma in string of dates
						
					  const datesArray = dates.split(",");//create array of dates 
					  const weekday = ["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"];
						
					  insertedHtml += "<table class = 'venue-table'><thead><tr><th>Available Date</th><th>Day of the week</th><th>Total cost for " + partySize + " people</th></tr></thead>";
					  for(let i = 0; i<datesArray.length; i++){//iterate through array of available dates add the date and corresponding info into a table row
					    let date = new Date(datesArray[i]);
						let day = weekday[date.getDay()];//returns day of week from weekday array
						insertedHtml += "<tbody><tr>";
						insertedHtml += "<td>" + datesArray[i] + "</td>" +
						"<td>" + day + "</td>";
						if(day == "Friday" || day == "Saturday" || day == "Sunday"){
						  let totalPriceWeekend = (cateringCost * partySize) + parseInt(weekendPrice);//cast string data from server into integer to do calculation
						  insertedHtml += "<td>£" + totalPriceWeekend + "</td>";
						}else{
					      let totalPriceWeekday = (cateringCost * partySize) + parseInt(weekdayPrice);//calculate total cost for entered capacity
						  insertedHtml += "<td>£" + totalPriceWeekday + "</td>";
						}
						insertedHtml += "</tr></tbody>";
					  }
					  insertedHtml +="</table>";
					
				    }else{//if the two dates entered are the same, different data from the server is supplied and needs to be handled differently 
					  const weekday = ["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"];
						
					  insertedHtml += "<table class = 'venue-table'><thead><tr><th>Available Date</th><th>Day of the week</th><th>Total cost for " + partySize + " people</th></tr></thead>";
					  let date = new Date(date1);
					  let day = weekday[date.getDay()];//returns day of week from weekday array
					  insertedHtml += "<tbody><tr>";
					  insertedHtml += "<td>" + date1 + "</td>" +
					  "<td>" + day + "</td>";
					  if(day == "Friday" || day == "Saturday" || day == "Sunday"){
						let totalPriceWeekend = (cateringCost * partySize) + parseInt(weekendPrice);//cast string data from server into integer to do calculation
						insertedHtml += "<td>£" + totalPriceWeekend + "</td>";
					  }else{
					    let totalPriceWeekday = (cateringCost * partySize) + parseInt(weekdayPrice);//calculate total cost for entered capacity
						insertedHtml += "<td>£" + totalPriceWeekday + "</td>";
					  }
					  insertedHtml += "</tr></tbody>";
					  insertedHtml +="</table>";
				    }
								
				    insertedHtml += "</div>" +
				    "<div class = 'flexbox item2'>" +
				    "<img src = '" + name + ".jpg' width=100% height=100%></div>" + //add venue specific image to results by using images named after the venue's name
				    "</div>";
				    insertedHtml += "<hr>";
				  }
				  insertedHtml += "</div>";		
				  $("#server-response").html(insertedHtml);
				}
			  },
			  error: function (xhr, status, error) {
			  console.log(xhr.status + ': ' + xhr.statusText);
			  },
			  dataType: "json"
			});
		  }else{
		    insertedHtml += "<h3>Error: the to date entered can not be a date before the from date</h3></div>";
			$("#server-response").html(insertedHtml);
		  }
	    });
	  });
    </script>
  </head>
	
  <body>
    <!--header-->
	<header id=header>
	  <h1>The Ultimate Brides Guide</h1>
	</header>
	<!--Navigation bar-->
	<nav>
	  <ul>
	    <li class="active"><a href="https://coss14.sci-project.lboro.ac.uk/f116673wedding/wedding.php">Home</a></li>
	    <li><a href="https://coss14.sci-project.lboro.ac.uk/f116673wedding/about.html">About Us</a></li>	
	  </ul>
	</nav>
		
    <main>
	  <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">
	    <div class="carousel-indicators">
		  <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
		  <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1" aria-label="Slide 2"></button>
		  <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2" aria-label="Slide 3"></button>
	    </div>
	    <div class="carousel-inner">
		  <div class="carousel-item active">
		    <img src="Forest Inn.jpg" class="d-block w-100" alt="...">
		    <div class="carousel-caption d-none d-md-block">
			  <h5>Find Your Perfect Venue</h5>
			  <p>We Offer Quick And Easy Wedding planning solutions</p>
		    </div>
		  </div>
		  <div class="carousel-item">
		    <img src="Haslegrave Hotel.jpg" class="d-block w-100" alt="...">
		    <div class="carousel-caption d-none d-md-block">
			  <h5>Find Your Perfect Venue</h5>
			  <p>We Offer Quick And Easy Wedding planning solutions</p>
		    </div>
		  </div>
		  <div class="carousel-item">
		    <img src="Southwestern Estate.jpg" class="d-block w-100" alt="...">
		    <div class="carousel-caption d-none d-md-block">
			  <h5>Find Your Perfect Venue</h5>
			  <p>We Offer Quick And Easy Wedding planning solutions</p>
		    </div>
		  </div>
	  </div>
	  <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
		<span class="carousel-control-prev-icon" aria-hidden="true"></span>
		<span class="visually-hidden">Previous</span>
	  </button>
	  <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
		<span class="carousel-control-next-icon" aria-hidden="true"></span>
		<span class="visually-hidden">Next</span>
	  </button>
	  </div>

	  <div class = "flexbox-container-info-cards">
		  <div class = "flexitem">
			  <div class="card" style="width: 18rem;">
			    <img src="Pacific Towers Hotel.jpg" class="card-img-top">
				<div class="card-body">
				  <h5 class="card-title">Determine Catering Cost</h5>
				  <p class="card-text">Find out the cost of catering per person</p>
				  <button type="button" class="btn btn-secondary" onclick="cateringGradeDisplay()">What is a catering grade?</button>
				</div>
			  </div>
		  </div>
		  <div class = "flexitem">
			  <div class="card" style="width: 18rem;">
			    <img src="Ashby Castle.jpg" class="card-img-top">
				<div class="card-body">
				  <h5 class="card-title">Find out more</h5>
				  <p class="card-text">The best selected Venues for everyones taste</p>
				  <a href="https://coss14.sci-project.lboro.ac.uk/f116673wedding/about.html" class="btn btn-secondary">About Us</a>
				</div>
			  </div>
		  </div>
		  <div class = "flexitem">
		    <div class="card" style="width: 18rem;">
			  <img src="Hilltop Mansion.jpg" class="card-img-top">
			  <div class="card-body">
			    <h5 class="card-title">Total cost?</h5>
			    <p class="card-text">We provide a total cost for your wedding</p>
			    <button type="button" class="btn btn-secondary" onclick="costDisplay()">how is total calculated?</button>
			  </div>
		    </div>
		  </div>
	  </div>


	  <div id = "catering-grade-info" style="display:none">
	    <p>
		  Each venue has a catering grade which determines the price per person for catering. The higher the catering grade the more expensive it is for catering, 
		  and likewise the higher the quality of catering provided.
		</p>
	  </div>

	  <div id = "cost-info" style="display:none">
	    <p>
		  A total cost is determined by two factors, whether it is a week day or weekend day that the venue is to be hired, and the capacity required for your wedding.
		  The total cost includes the amount to hire the venue for that specific day of the week as well as the total catering cost for the specified capacity.
		</p>
	  </div>

	  <div id = "date-explanation-element">
	    <p id = "date-explanation-text">
	      Please complete and submit the form below to find available venues. 
	      Entering the two dates below will provide the availability for suitable venues between those dates. 
	    </p>
	  </div>

	  <form id = "venue-form">
	    <div class = "form">
	      <div class = "flexbox-container-inputs">
		    <label for="start-date" class="form-label">From date:</label>
			<input name = "start-date" type="date" class="form-control" id="start-date"  min = "2022-01-02" required>
			<label for="end-date" class="form-label">To date:</label>
			<input name = "end-date" type="date" class="form-control" id="end-date" min="2022-01-02" required>
			<label for="capacity" class="form-label">Required capacity:</label>
			<input name = "capacity" type="number" class="form-control" id="capacity" placeholder="50" min="1" max="1000" required>
			<label for="grade" class="form-label">Catering grade:</label>
			<input name = "grade" type="number" class="form-control" id="grade" min="1" max="5" value="1" required>
		  </div>
			
		  <div id = "submit-div-element">
		    <input type = "submit" id = "submit-button" value = "Find Venue">
		  </div>
			
		  <input type="reset" id = "reset-input">
	    </div>
	  </form>

	  <div id = "server-response">
	  </div>
	</main>
	<footer id="footer">
	  <h4>Contact details</h4>
	  <ul class="contact">
	    <li class="contact-details">F116673@theultimatebridesguide.com</li>
		<li class="contact-details">(+44) 79493090821</li>
		<li class="contact-details">(+27) 716033436</li>
	  </ul>
	</footer>
		
	<script>
	  const gradeInfo = document.getElementById("catering-grade-info");//get div element which holds catering grade information
	  const costInfo = document.getElementById("cost-info");//get div element which holds cost information
		
	  function cateringGradeDisplay(){//function to toggle between displaying the div element with id = 'catering-grade-info'
	    if(costInfo.style.display == "block"){//check if costInfo is being displayed and if so remove this to display grdeInfo
		  costInfo.style.display = "none";
		  }
			
		if (gradeInfo.style.display == "none"){
		  gradeInfo.style.display = "block";
		}else{
		  gradeInfo.style.display = "none";
		}
	  }
		
	  function costDisplay(){//function to toggle between displaying the div element with id = 'cost-info'
	    if(gradeInfo.style.display == "block"){//check if gradeInfo is being displayed and if so remove this to display costInfo
		  gradeInfo.style.display = "none";
		}
			
		if (costInfo.style.display == "none"){
		  costInfo.style.display = "block";
		}else{
		  costInfo.style.display = "none";
		}
	  }

	  document.getElementById("start-date").setAttribute("min", new Date().toISOString().split('T')[0]);//set minimum date for input to todays date
	  document.getElementById("end-date").setAttribute("min", new Date().toISOString().split('T')[0]);//set minimum date for input to todays date
			
	</script>

	<?php
	?>
	
  </body>
</html>