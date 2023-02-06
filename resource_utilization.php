
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
						<a href=""  class="active">
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
        <h1 style="color: #3f2b96;">Resource Utlizilation</h1>
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
          <th scope="col">Class size</th>
          <th scope="col">IUB resource</th>
          <th scope="col">Semester</th>
          <th scope="col">Difference</th>
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
    CASE 
        WHEN room_capacity BETWEEN 1 AND 20 THEN '20'
        WHEN room_capacity BETWEEN 21 AND 30 THEN '30'
        WHEN room_capacity BETWEEN 31 AND 35 THEN '35'
        WHEN room_capacity BETWEEN 36 AND 40 THEN '40'
        WHEN room_capacity BETWEEN 41 AND 50 THEN '50'
        WHEN room_capacity BETWEEN 51 AND 54 THEN '54'
        WHEN room_capacity BETWEEN 55 AND 64 THEN '64'
        WHEN room_capacity BETWEEN 65 AND 124 THEN '124'
        WHEN room_capacity BETWEEN 125 AND 168 THEN '168'
        WHEN room_capacity THEN room_capacity
      END AS classsize,
      COUNT(DISTINCT(course_section.roomId)) AS IUB_resources,
      ROUND(COUNT(CASE WHEN enrolled > 0 THEN 1 END) / 12, 1) AS semester,
      COUNT(DISTINCT(course_section.roomId)) - ROUND(COUNT(CASE WHEN enrolled > 0 THEN 1 END) / 12, 1) AS Difference
  FROM course_section
  LEFT JOIN classroom ON course_section.roomId = classroom.roomId
  WHERE semester = '$semester' AND year = '$year' OR enrolled = 0
  GROUP BY classsize
  HAVING classsize IS NOT NULL
  UNION
  SELECT 
    'Total',
      COUNT(DISTINCT(course_section.roomId)) AS IUB_resources,
      ROUND(COUNT(CASE WHEN enrolled > 0 THEN 1 END) / 12, 1) AS semester,
      COUNT(DISTINCT(course_section.roomId)) - ROUND(COUNT(CASE WHEN enrolled > 0 THEN 1 END) / 12, 1) AS Difference
  FROM course_section
  LEFT JOIN classroom ON course_section.roomId = classroom.roomId
  WHERE (semester = '$semester' AND year = '$year' OR enrolled = 0) AND room_capacity != 0;";
    $result = $connection->query($sql);

    if (!$result){
        die("Invalid query: " . $connection->error);
    }

    while($row = $result->fetch_assoc()){ echo "
        <tr>
          <td>$row[classsize]</td>
          <td>$row[IUB_resources]</td>
          <td>$row[semester]</td>
          <td>$row[Difference]</td>
        </tr>
        "; 
    }

    $semester = array();
        foreach($result as $data){
          $classsize[] = $data['classsize'];
          $iub_resources[] = $data['IUB_resources'];
          $semester[] = $data['semester'];
          $difference[] = $data['Difference'];
        }
      }
      }
?>
      </tbody>
    </table>
    <div style="width: 800px">
		<canvas id="myChart"></canvas>
	  </div>
	  </div>
	  </div>
	
    </div>


	<!-- JS for Charts -->
	<script>
		const ctx = document.getElementById('myChart');

        const DATA_COUNT = 6;
const NUMBER_CFG = {count: DATA_COUNT, min: -100, max: 100};

const labels = <?php echo json_encode($classsize)?>;
const data = {
labels: labels,
datasets: [
  {
    type: 'bar',
    label: 'IUB resource',
    data: <?php echo json_encode($iub_resources)?>,
  },
  {
    type: 'bar',
    label: 'Semester',
    data: <?php echo json_encode($semester)?>,
  }
]
};


const config = {
type: 'bar',
data: data,
options: {
  responsive: true,
  plugins: {
    legend: {
      position: 'top',
    },
    title: {
      display: true,
      text: 'Resource utilization bar chart',
    }
  }
},
};

		new Chart(ctx, config);
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
