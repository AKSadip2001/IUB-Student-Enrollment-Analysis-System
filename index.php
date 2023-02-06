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
						<a href=""  class="active">
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
        	<h1 style="color: #3f2b96;">Classroom requirement</h1>
    		<form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
				<div class="dropdown" style="margin: 30px; display: inline">
					<select id="select1" name="semester">
						<option value="">Semester</option>
						<option value="Spring">Spring</option>
						<option value="Summer">Summer</option>
						<option value="Autumn">Autumn</option>
					</select>
				</div>

				<div class="dropdown" style="margin: 30px; display: inline">
					<select id="select2" name="year">
					<option value="">Year</option>
					<?php
						$servername = "localhost";
						$username = "username";
						$password = "";
						$database = "seas";
					
						$connection = new mysqli($servername, $username, $password, $database);
					
						if($connection->connect_error){
							die("Connection failed: " . $connection->connect_error);
						}

						$sql = "SELECT DISTINCT(year) FROM `course_section`;";
						$list = $connection->query($sql);
			
						if (!$list){
							die("Invalid query: " . $connection->error);
						}

						
						while($row_list = $list->fetch_assoc()){
					?>

					<option value="<?php echo $row_list['year']; ?>">
						<?php echo $row_list['year']; ?>
					</option>
						<?php
						}
						?>
					</select>
				</div>

				<input
					class="btn btn-primary"
					type="submit"
					value="View"
					style="margin: 30px; display: inline"
				/>
			</form>
			<table class="table" style="width: 800px">
				<thead>
					<tr>
						<th scope="col">Class Size</th>
						<th scope="col">Sections</th>
						<th scope="col">Classroom-6</th>
						<th scope="col">Classroom-7</th>
					</tr>
				</thead>
				<tbody>
					<?php
						if ($_SERVER["REQUEST_METHOD"] == "POST") {
							// collect value of input field
							$semester = $_POST['semester'];
							$year = $_POST['year'];
							if (empty($semester) || empty($year)) {
								echo "Select from drop down menu!";
							}
							else{
								$servername = "localhost";
								$username = "username";
								$password = "";
								$database = "seas";
							
								$connection = new mysqli($servername, $username, $password, $database);
							
								if($connection->connect_error){
									die("Connection failed: " . $connection->connect_error);
								}
							
								$sql = "SELECT
								CASE WHEN enrolled BETWEEN 1 AND 10 then '1-10'
								WHEN enrolled BETWEEN 11 AND 20 then '11-20'
								WHEN enrolled BETWEEN 21 AND 30 then '21-30'
								WHEN enrolled BETWEEN 31 AND 35 then '31-35'
								WHEN enrolled BETWEEN 36 AND 40 then '36-40'
								WHEN enrolled BETWEEN 41 AND 50 then '41-50'
								WHEN enrolled BETWEEN 51 AND 55 then '51-55'
								WHEN enrolled BETWEEN 56 AND 65 then '56-65'
								END AS classsize,
								COUNT(section) AS sections,
								COUNT(section) / 12 AS classroom6,
								COUNT(section) / 14 AS classroom7
								FROM course_section
								WHERE semester = '$semester' AND year = '$year'
								GROUP BY classsize
								HAVING classsize IS NOT NULL
								UNION
								SELECT 'Total' AS classsize, 
								COUNT(section) AS sections, 
								COUNT(section) / 12 AS classroom6, 
								COUNT(section) / 14 AS classroom7
								FROM course_section
								WHERE enrolled BETWEEN 1 AND 65 AND semester = '$semester' AND year = '$year';";
								$result = $connection->query($sql);
							
								if (!$result){
									die("Invalid query: " . $connection->error);
								}
							
								while($row = $result->fetch_assoc()){ echo "
									<tr>
										<td>$row[classsize]</td>
										<td>$row[sections]</td>
										<td>$row[classroom6]</td>
										<td>$row[classroom7]</td>
									</tr>
									"; 
								}
							
								foreach($result as $data){
									$classize[] = $data['classsize'];
									$sections[] = $data['sections'];
									$classroom6[] = $data['classroom6'];
									$classroom7[] = $data['classroom7'];
								}
								array_pop($classize);
								array_pop($sections);
								array_pop($classroom6);
								array_pop($classroom7);
							}
						}
					?>
				</tbody>
			</table>
	  	</div>
  
		<div class="d-flex flex-row justify-content-center mt-3 mb-5" >
			<div style="width: 400px">
				<canvas id="myChart1"></canvas>
			</div>
			<div style="width: 400px">
				<canvas id="myChart2"></canvas>
			</div>
		</div>
    </div>


	<!-- JS for Charts -->
	<script>
		const ctx1 = document.getElementById('myChart1');
		const ctx2 = document.getElementById('myChart2');

		const DATA_COUNT = 8;
		const NUMBER_CFG = {count: DATA_COUNT, min: 0, max: 100};

		const data1 = {
			labels: <?php echo json_encode($classize)?>,
			datasets: [
				{
				data: <?php echo json_encode($classroom6)?>,
				}
			]
		};

		const config1 = {
			type: 'pie',
			data: data1,
			options: {
				responsive: true,
				plugins: {
				legend: {
					position: 'top',
				},
				title: {
					display: true,
					text: 'Classroom-6'
				}
				}
			},
		};

		new Chart(ctx1, config1);

		const data2 = {
			labels: <?php echo json_encode($classize)?>,
			datasets: [
				{
				data: <?php echo json_encode($classroom7)?>,
				}
			]
		};

		const config2 = {
			type: 'pie',
			data: data2,
			options: {
				responsive: true,
				plugins: {
				legend: {
					position: 'top',
				},
				title: {
					display: true,
					text: 'Classroom-7'
				}
				}
			},
		};
		new Chart(ctx2, config2);
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
