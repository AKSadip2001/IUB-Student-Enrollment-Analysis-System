
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
	<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-trendline"></script>

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
						<a href="http://localhost/Project/revenue_of_schools.php">
							<i class='bx bxs-widget icon'></i>
							Revenue of schools
						</a>
					</li>

					<li>
						<a href=""  class="active">
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
			<h1 class="mb-5" style="color: #3f2b96;">Revenue of Engineering School</h1>
			<table class="table" style="width: 800px">
				<thead>
				<tr>
					<th scope="col">Semester</th>
					<th scope="col">CSE</th>
					<th scope="col">EEE</th>
					<th scope="col">PS</th>
					<th scope="col">SETS</th>
					<th scope="col">CSE%</th>
					<th scope="col">EEE%</th>
					<th scope="col">PS%</th>
					<th scope="col">SETS%</th>
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
					SUM(CASE WHEN C.school_title='SETS' AND C.courseId LIKE 'CCR%' OR C.courseId LIKE 'CNC%' OR C.courseId LIKE 'CEN%' OR  C.courseId LIKE 'SEN%' OR C.courseId LIKE 'CIS%' OR C.courseId LIKE 'CSC%' OR C.courseId LIKE 'CSE%' THEN CS.enrolled*C.credit_hour END) as CSE,
					SUM(CASE WHEN C.school_title='SETS' AND C.courseId LIKE 'ETE%' OR C.courseId LIKE 'ECR%' OR C.courseId LIKE 'EEE%' THEN CS.enrolled*C.credit_hour END) as EEE,			
					SUM(CASE WHEN C.school_title='SETS' AND C.courseId LIKE 'PHY%' OR C.courseId LIKE 'MAT%' THEN CS.enrolled*C.credit_hour END) as PS,			
					SUM(CASE WHEN C.school_title='SETS' THEN CS.enrolled*C.credit_hour END) as SETS,			
					((SUM(CASE WHEN C.school_title='SETS' AND C.courseId LIKE 'CCR%' OR C.courseId LIKE 'CNC%' OR C.courseId LIKE 'CEN%' OR  C.courseId LIKE 'SEN%' OR C.courseId LIKE 'CIS%' OR C.courseId LIKE 'CSC%' OR C.courseId LIKE 'CSE%' THEN CS.enrolled*C.credit_hour END) - (SELECT SUM(course_section.enrolled*course.credit_hour) FROM course_section, course WHERE course_section.year=CS.year-1 AND course_section.semester=CS.semester AND course.school_title='SETS' AND course_section.courseId=course.courseId  AND course_section.blocked NOT IN ('B-') AND (course.courseId LIKE 'CCR%' OR course.courseId LIKE 'CNC%' OR course.courseId LIKE 'CEN%' OR  course.courseId LIKE 'SEN%' OR course.courseId LIKE 'CIS%' OR course.courseId LIKE 'CSC%' OR course.courseId LIKE 'CSE%')))/SUM(CASE WHEN C.school_title='SETS' AND C.courseId LIKE 'CCR%' OR C.courseId LIKE 'CNC%' OR C.courseId LIKE 'CEN%' OR  C.courseId LIKE 'SEN%' OR C.courseId LIKE 'CIS%' OR C.courseId LIKE 'CSC%' OR C.courseId LIKE 'CSE%' THEN CS.enrolled*C.credit_hour END))*100 as 'CSE_CHG',
					((SUM(CASE WHEN C.school_title='SETS' AND C.courseId LIKE 'ETE%' OR C.courseId LIKE 'ECR%' OR C.courseId LIKE 'EEE%' THEN CS.enrolled*C.credit_hour END) - (SELECT SUM(course_section.enrolled*course.credit_hour) FROM course_section, course WHERE course_section.year=CS.year-1 AND course_section.semester=CS.semester AND course.school_title='SETS' AND course_section.courseId=course.courseId  AND course_section.blocked NOT IN ('B-') AND (course.courseId LIKE 'ETE%' OR course.courseId LIKE 'ECR%' OR course.courseId LIKE 'EEE%')))/SUM(CASE WHEN C.school_title='SETS' AND C.courseId LIKE 'ETE%' OR C.courseId LIKE 'ECR%' OR C.courseId LIKE 'EEE%' THEN CS.enrolled*C.credit_hour END))*100 as 'EEE_CHG',
					((SUM(CASE WHEN C.school_title='SETS' AND C.courseId LIKE 'PHY%' OR C.courseId LIKE 'MAT%' THEN CS.enrolled*C.credit_hour END) - (SELECT SUM(course_section.enrolled*course.credit_hour) FROM course_section, course WHERE course_section.year=CS.year-1 AND course_section.semester=CS.semester AND course.school_title='SETS' AND course_section.courseId=course.courseId  AND course_section.blocked NOT IN ('B-') AND (course.courseId LIKE 'PHY%' OR course.courseId LIKE 'MAT%')))/SUM(CASE WHEN C.school_title='SETS' AND C.courseId LIKE 'PHY%' OR C.courseId LIKE 'MAT%' THEN CS.enrolled*C.credit_hour END))*100 as 'PS_CHG',			
					(SUM(CASE WHEN C.school_title='SETS' THEN CS.enrolled*C.credit_hour END) - (SELECT SUM(course_section.enrolled*course.credit_hour) FROM course_section, course WHERE course_section.year=CS.year-1 AND course_section.semester=CS.semester AND course.school_title='SETS' AND course_section.courseId=course.courseId  AND course_section.blocked NOT IN ('B-')))/(SUM(CASE WHEN C.school_title='SETS' THEN CS.enrolled*C.credit_hour END))*100 as 'SETS_CHG' FROM course_section as CS, course as C WHERE CS.courseId=C.courseId AND CS.blocked NOT IN ('B-') GROUP BY year, semester ORDER BY semester;";
					
					$result = $connection->query($sql);
				
					if (!$result){
						die("Invalid query: " . $connection->error);
					}
				
					while($row = $result->fetch_assoc()){ echo "
						<tr>
							<td>$row[Semester]</td>
							<td>$row[CSE]</td>
							<td>$row[EEE]</td>
							<td>$row[PS]</td>
							<td>$row[SETS]</td>
							<td>$row[CSE_CHG]</td>
							<td>$row[EEE_CHG]</td>
							<td>$row[PS_CHG]</td>
							<td>$row[SETS_CHG]</td>
						</tr>
						"; 
					}
				
					foreach($result as $data){
						$semester[] = $data['Semester'];
						$CSE[] = $data['CSE'];
						$EEE[] = $data['EEE'];
						$PS[] = $data['PS'];
						$SETS[] = $data['SETS'];
						$CSE_CHG[] = $data['CSE_CHG'];
						$EEE_CHG[] = $data['EEE_CHG'];
						$PS_CHG[] = $data['PS_CHG'];
						$SETS_CHG[] = $data['SETS_CHG'];
					}
					?>
				</tbody>
			</table>

			<div class="d-flex flex-column align-items-center m-5" style="width: 1000px">
				<h3 class="mb-3"  style="color: #3f2b96;">Department wise revenue in SETS</h3>
				<canvas id="myChart1"></canvas>
			</div>

			<div class="d-flex flex-column align-items-center m-5" style="width: 1000px">
				<h3 class="mb-3"  style="color: #3f2b96;">Revenue in SETS</h3>
				<canvas id="myChart2"></canvas>
			</div>

			<div class="d-flex flex-column align-items-center m-5" style="width: 1000px">
				<h3 class="mb-3"  style="color: #3f2b96;">CSE Revenue and Change %</h3>
				<canvas id="myChart3"></canvas>
			</div>

			<div class="d-flex flex-column align-items-center m-5" style="width: 1000px">
				<h3 class="mb-3"  style="color: #3f2b96;">EEE Department</h3>
				<canvas id="myChart4"></canvas>
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
				label: 'CSE',
				data: <?php echo json_encode($CSE)?>
			},
			{
				label: 'EEE',
				data: <?php echo json_encode($EEE)?>
			},
			{
				label: 'PS',
				data: <?php echo json_encode($PS)?>
			}
			]
		};

		const config = {
			type: 'line',
			data: data,
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
					label: 'CSE', // Name the series
					data: <?php echo json_encode($CSE)?>,
					fill: true,
					borderWidth: 1
				},
				{
					label: 'EEE', // Name the series
					data: <?php echo json_encode($EEE)?>,
					fill: true,
					borderWidth: 1
				},
				{
					label: 'PS', // Name the series
					data: <?php echo json_encode($PS)?>,
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
				label: 'CSE%',
				data: <?php echo json_encode($CSE_CHG)?>,
				type: 'bar',
				order: 1,
				yAxisID: 'y1'
			},
			{
				label: 'CSE',
				data: <?php echo json_encode($CSE)?>,
				type: 'line',
				order: 0,
				yAxisID: 'y',
				trendlineLinear: {
					lineStyle: "dotted",
					width: 2
				}
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

	<script>
		const ctx4 = document.getElementById('myChart4');


		new Chart(ctx4, {
		data: {
		labels: <?php echo json_encode($semester)?>,
		datasets: [
			{
			label: 'EEE%',
			data: <?php echo json_encode($EEE_CHG)?>,
			type: 'bar',
			order: 1,
			yAxisID: 'y1'
			},
			{
			label: 'EEE',
			data: <?php echo json_encode($EEE)?>,
			type: 'line',
			order: 0,
			yAxisID: 'y',
			trendlineLinear: {
				lineStyle: "dotted",
				width: 2
			}
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
