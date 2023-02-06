
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>SEAS</title>

	<link rel="stylesheet" href="assets/modules/bootstrap-5.1.3/css/bootstrap.css">
	<link rel="stylesheet" href="./CSS/web.css">
	<link rel="stylesheet" href="assets/modules/fontawesome6.1.1/css/all.css">
	<link rel="stylesheet" href="assets/modules/boxicons/css/boxicons.min.css">
	<link rel="stylesheet" href="assets/modules/apexcharts/apexcharts.css">
	<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>
<body>
  
 
	
	<!--Sidebar-->
	<div class="sidebar transition overlay-scrollbars animate__animated  animate__slideInLeft">
		<div class="sidebar-content">
			<div id="sidebar">
	
				<!-- Logo -->
				<div class="logo">
					<h2 class="mb-0"><img src="./assets/Image/logo.png"> IUB</h2>
				</div>
	
				<ul class="side-menu">
					<li>
						<a href="http://localhost/Project/index.php">
							<i class='bx bxs-dashboard icon'></i> Classroom requirement
						</a>
					</li>

					<li>
						<a href="http://localhost/Project/class_distribution.php" >
							<i class='bx bxs-widget icon'></i>
							Class-size distributions
						</a>
					</li>
					<li>
						<a href="http://localhost/Project/resource_summaries.php">
							<i class='bx bxs-bar-chart-alt-2 icon'></i>
							Resource summaries
						</a>
					</li>
					<li>
						<a href="#"  class="active">
							<i class='bx bxs-widget icon'></i>
							Avaiable resources
						</a>
					</li>
					<li>
						<a href="http://localhost/Project/resource_utilization.php">
							<i class="bx bx-columns icon"></i>
							Resource utlizilation
						</a>
					</li>
	
					<li>
						<a href="http://localhost/Project/enrollment_breakdown.php">
							<i class='bx bxs-widget icon'></i>
							Enrollment breakdown
						</a>
					</li>
					
					<li>
						<a href="http://localhost/Project/revenue_of_schools.php">
							<i class='bx bxs-widget icon'></i>
							Revenue of schools
						</a>
					</li>

					<li>
						<a href="http://localhost/Project/revenue_of_sets.php">
							<i class='bx bxs-widget icon'></i>
							Revenue of SETS
						</a>
					</li>
					
					<li>
						<a href="log_in.php">
							<i class='fa fa-sign-out icon'></i>
							Log out
						</a>
					</li>
				</ul>
			</div>
	
		</div>
	</div>
	</div>


	<!--Content Start-->
	<div class="content-start transition">
		<div class="d-flex flex-column align-items-center">
            <h1 style="color: #3f2b96;">Available resources</h1>
        </div>

      <div class="d-flex flex-row justify-content-around mt-5">
      <table class="table" style="width: 600px">
      <thead>
        <tr>
          <th scope="col">Class size</th>
          <th scope="col">IUB resource</th>
          <th scope="col">Capacity</th>
        </tr>
      </thead>
      <tbody>
        <?php

    $servername = "localhost";
    $username = "username";
    $password = "";
    $database = "seas";

    $connection = new mysqli($servername, $username, $password, $database);

    if($connection->connect_error){
        die("Connection failed: " . $connection->connect_error);
    }

    $sql = "SELECT room_capacity AS 'Class_Size',
    COUNT(roomId) AS 'IUB_Resource', 
    (room_capacity*COUNT(roomId)) AS 'Capacity'  
    FROM classroom
    GROUP BY room_capacity
    UNION
    SELECT 'Total' AS 'Class_Size',
    COUNT(roomId) AS 'IUB_Resource',
    SUM(room_capacity) AS 'Capacity'
    FROM classroom;";
    $result = $connection->query($sql);

    if (!$result){
        die("Invalid query: " . $connection->error);
    }


    while($row = $result->fetch_assoc()){ echo "
        <tr>
          <td>$row[Class_Size]</td>
          <td>$row[IUB_Resource]</td>
          <td>$row[Capacity]</td>
        </tr>
        "; 
    }
?>
      </tbody>
    </table>

    <table class="table" style="width: 400px">
      <thead>
        <tr>
          <th scope="col"></th>
          <th scope="col">Total capacity</th>
        </tr>
      </thead>
      <tbody>
        <?php
    $servername = "localhost";
    $username = "username";
    $password = "";
    $database = "seas";

    $connection = new mysqli($servername, $username, $password, $database);

    if($connection->connect_error){
        die("Connection failed: " . $connection->connect_error);
    }

    $sql = "SELECT 'Total Capacity with 6 slot 2 days' as 'Category',
    
    SUM(room_capacity) * 12 AS 'Capacity'
    FROM classroom
    UNION
    SELECT 'Total Capacity with 7 slot 2 days',
   
    SUM(room_capacity) * 14 AS 'Capacity'
    FROM classroom
    UNION
    SELECT 'Considering 3.5 average course load (6 slot)',
   
    ROUND(SUM(room_capacity) * 12 / 3.5, 0) 'Capacity'
    FROM classroom
    UNION
    SELECT 'Considering 3.5 average course load (6 slot)',
   
    ROUND(SUM(room_capacity) * 14 / 3.5, 0) 'Capacity'
    FROM classroom;";
    $result = $connection->query($sql);

    if (!$result){
        die("Invalid query: " . $connection->error);
    }


    while($row = $result->fetch_assoc()){ echo "
        <tr>
          <td>$row[Category]</td>
          <td>$row[Capacity]</td>
        </tr>
        "; 
    }
?>
      </tbody>
    </table>

      </div>
	  </div>
	
    </div>

	<!-- General JS Scripts -->
	<script src="assets/js/atrana.js"></script>

	<!-- JS Libraies -->
	<script src="assets/modules/jquery/jquery.min.js"></script>
	<script src="assets/modules/bootstrap-5.1.3/js/bootstrap.bundle.min.js"></script>
	<script src="assets/modules/popper/popper.min.js"></script>


    <!-- Template JS File -->
	<script src="assets/js/script.js"></script>
	<script src="assets/js/custom.js"></script>
 </body>
</html>
