
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>SEAS</title>

	<link rel="stylesheet" href="assets/modules/bootstrap-5.1.3/css/bootstrap.css">
	<link rel="stylesheet" href="web.css">
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
						<a href="http://localhost/Project/class_distribution.php">
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
						<a href="http://localhost/Project/available_resources.php">
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
						<a href=""  class="active">
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

	<!--Content Start-->
	<div class="content-start transition">
		<div class="d-flex flex-column align-items-center">
			<h1 class="mb-5" style="color: #3f2b96;">Revenue of Schools Analysis</h1>
			<table class="table" style="width: 800px">
				<thead>
				<tr>
					<th scope="col">Semester</th>
					<th scope="col">SBE</th>
					<th scope="col">SETS</th>
					<th scope="col">SELS</th>
					<th scope="col">SLASS</th>
					<th scope="col">SPPH</th>
					<th scope="col">Total</th>
					<th scope="col">Difference</th>
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
					
						$sql = "SELECT CONCAT(CS.year, CASE WHEN CS.semester='Autumn' THEN '3' WHEN CS.semester='Spring' THEN '1' WHEN CS.semester='Summer' THEN '2' END, CS.semester) as Semester, 
						SUM(CASE WHEN C.school_title='SBE' THEN CS.enrolled*C.credit_hour END) as SBE, 
						SUM(CASE WHEN C.school_title='SETS' THEN CS.enrolled*C.credit_hour END) as SETS, 
						SUM(CASE WHEN C.school_title='SELS' THEN CS.enrolled*C.credit_hour END) as SELS, 
						SUM(CASE WHEN C.school_title='SLASS' OR (C.school_title='SLASS' AND C.school_title='SPPH' AND (CS.courseId LIKE 'HEA%' OR CS.courseId LIKE 'PHA%' OR CS.courseId LIKE 'PSY%' OR CS.courseId LIKE 'POP%') AND year<2015) THEN CS.enrolled*C.credit_hour END) as SLASS, 
						SUM(CASE WHEN C.school_title='SPPH' AND year>=2015 THEN CS.enrolled*C.credit_hour END) as SPPH,
						SUM(CS.enrolled*C.credit_hour) as Total, 
						((SUM(CS.enrolled*C.credit_hour) - (SELECT SUM(course_section.enrolled*course.credit_hour) FROM course_section, course WHERE course_section.year=CS.year-1 AND course_section.semester=CS.semester AND course_section.courseId=course.courseId AND course_section.blocked NOT IN ('B-')))/SUM(CS.enrolled*C.credit_hour))*100 as DIFFERENCE 
						FROM course_section as CS, course as C WHERE CS.courseId=C.courseId AND CS.blocked NOT IN ('B-') GROUP BY year, semester ORDER BY semester;";

						$result = $connection->query($sql);
					
						if (!$result){
							die("Invalid query: " . $connection->error);
						}
					
						while($row = $result->fetch_assoc()){ echo "
							<tr>
								<td>$row[Semester]</td>
								<td>$row[SBE]</td>
								<td>$row[SETS]</td>
								<td>$row[SELS]</td>
								<td>$row[SLASS]</td>
								<td>$row[SPPH]</td>
								<td>$row[Total]</td>
								<td>$row[DIFFERENCE]</td>
							</tr>
							"; 
						}
					
						foreach($result as $data){
							$semester[] = $data['Semester'];
							$SBE[] = $data['SBE'];
							$SETS[] = $data['SETS'];
							$SELS[] = $data['SELS'];
							$SLASS[] = $data['SLASS'];
							$SPPH[] = $data['SPPH'];
							$Total[] = $data['Total'];
							$difference[] = $data['DIFFERENCE'];
						}
					?>
				</tbody>
			</table>

			<div class="d-flex flex-column align-items-center m-5" style="width: 1000px">
				<h3 class="mb-3"  style="color: #3f2b96;">Revenue Trend of the schools</h3>
				<canvas id="myChart1"></canvas>
			</div>

			<div class="d-flex flex-column align-items-center m-5" style="width: 1000px">
				<h3 class="mb-3"  style="color: #3f2b96;">Revenue Distribution among the schools</h3>
				<canvas id="myChart2"></canvas>
			</div>

			<div class="d-flex flex-column align-items-center m-5" style="width: 1000px">
				<h3 class="mb-3"  style="color: #3f2b96;">IUB Revenue and Change %</h3>
				<canvas id="myChart3"></canvas>
			</div>

		</div>
    </div>


	<!-- JS for Charts -->
	<script>
		const ctx = document.getElementById('myChart1');

		const labels = <?php echo json_encode($semester)?>;
		const data = {
		labels: labels,
			datasets: [{
				label: 'SBE',
				data: <?php echo json_encode($SBE)?>
			},
			{
				label: 'SETS',
				data: <?php echo json_encode($SETS)?>
			},
			{
				label: 'SELS',
				data: <?php echo json_encode($SELS)?>
			},
			{
				label: 'SLASS',
				data: <?php echo json_encode($SLASS)?>
			},
			{
				label: 'SBE',
				data: <?php echo json_encode($SPPH)?>
			},
			{
				label: 'Total',
				data: <?php echo json_encode($Total)?>
			}
			]
		};

		const config = {
			type: 'line',
			data: data
		};

		new Chart(ctx, config);

	</script>

	<script>
		var ctx2 = document.getElementById("myChart2");

		new Chart(ctx2, {
			type: 'line',
			data: {
				labels: <?php echo json_encode($semester)?>,
				datasets: [{
					label: 'SBE', // Name the series
					data: <?php echo json_encode($SBE)?>,
					fill: true,
					borderWidth: 1
				},
				{
					label: 'SETS', // Name the series
					data: <?php echo json_encode($SETS)?>,
					fill: true,
					borderWidth: 1
				},
				{
					label: 'SELS', // Name the series
					data: <?php echo json_encode($SELS)?>,
					fill: true,
					borderWidth: 1
				},
				{
					label: 'SLASS', // Name the series
					data: <?php echo json_encode($SLASS)?>,
					fill: true,
					borderWidth: 1
				},
				{
					label: 'SPPH', // Name the series
					data: <?php echo json_encode($SPPH)?>,
					fill: true,
					borderWidth: 1
				},
				{
					label: 'Total', // Name the series
					data: <?php echo json_encode($Total)?>,
					fill: true,
					borderWidth: 1
				}]
			}
		}
		);
	</script>

	<script>
		const ctx3 = document.getElementById('myChart3');


		new Chart(ctx3, {
		data: {
		labels: <?php echo json_encode($semester)?>,
		datasets: [
			{
			label: 'Change %',
			data: <?php echo json_encode($difference)?>,
			type: 'bar',
			order: 1,
			yAxisID: 'y1'
			},
			{
			label: 'Total',
			data: <?php echo json_encode($Total)?>,
			type: 'line',
			order: 0,
			yAxisID: 'y',
			}
		]
		},
		options: {
			responsive: true,
			interaction: {
			mode: 'index',
			intersect: false,
			},
			stacked: false,
			scales: {
			y: {
				type: 'linear',
				display: true,
				position: 'left',
			},
			y1: {
				type: 'linear',
				display: true,
				position: 'right',

				// grid line settings
				grid: {
				drawOnChartArea: false, // only want the grid lines for one axis to show up
				},
			},
			}
		}
		}
		);
	</script>

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
