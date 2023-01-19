# Web-Programming-Coursework
During my first year at Loughborough University I was required to design and implement a website for the Web-Programming module.

I achieved 85% for this coursework.

This website application is a wedding venue finder website which conects to a mySQL database, where the database stores data regarding different wedding venues.

I have designed and programmed the website so that it has a user friendly and responsive design.

The website takes as input from the user either a specific date or range of dates that the user might want to book a wedding venue for. The system also takes as input 
the capacity, and catering grade the user wants for their potential wedding venue. 

After the system has validated the input from the user, using PHP and SQL the website connects to a mySQL database. Furthermore using JSon and Ajax the website displays 
the wedding venues that are available on the given days which can host the requested capacity, and cater the requested catering grade. The Ajax allows for the
quick display of the available wedding venues without reloading the webpage.


Specification of Coursework:
Design your own webpage (must start from wedding.php) to find suitable wedding venue(s) based on date, party size and catering grade provided by users.

A basic solution to this task would potentially obtain up to 40%. You can be creative of the webpage design and show the venue results informatively to users. The results should include the venue name, capacity, licensed, catering cost per person, price of week/weekend day, total price, the number of times that the venue had been booked before (to show the user how much the venue is popular), and the day of week (Monday, Tuesday, …) for the selected date. To make your webpage faster and more interactive, you need to consider using AJAX and JSON (15% available for effective use of AJAX and JSON). To make your solution more flexible, you could allow the user to enter a range of dates, rather than just a single date and present them with several possibilities. You may assume that the range of dates cover no more than a one-week period (10% available for implementing this functionality).


