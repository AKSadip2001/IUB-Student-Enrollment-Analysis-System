
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
						<a href=""  class="active">
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
      <h1 style="color: #3f2b96;">Enrollment wise course distribution among the schools</h1>
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
            <th scope="col">Enrollment</th>
            <th scope="col">SBE</th>
            <th scope="col">SELS</th>
            <th scope="col">SETS</th>
            <th scope="col">SLASS</th>
            <th scope="col">SPPH</th>
            <th scope="col">Total</th>
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
                CASE WHEN enrolled BETWEEN 1 AND 10 THEN '1-10'
                WHEN enrolled BETWEEN 11 AND 20 THEN '11-20'
                WHEN enrolled BETWEEN 21 AND 30 THEN '21-30'
                WHEN enrolled BETWEEN 31 AND 35 THEN '31-35'
                WHEN enrolled BETWEEN 36 AND 40 THEN '36-40'
                WHEN enrolled BETWEEN 41 AND 50 THEN '41-50'
                WHEN enrolled BETWEEN 51 AND 55 THEN '51-55'
                WHEN enrolled BETWEEN 56 AND 60 THEN '56-60'
                WHEN enrolled > 60 THEN '60+' 
                END AS Enrollment,
                COUNT(CASE WHEN course.school_title='SBE' THEN 'SBE' END) AS SBE,
                COUNT(CASE WHEN course.school_title='SELS' THEN 'SELS' END) AS SELS,
                COUNT(CASE WHEN course.school_title='SETS' THEN 'SETS' END) AS SETS,
                COUNT(CASE WHEN course.school_title='SLASS' THEN 'SLASS' END) AS SLASS,
                COUNT(CASE WHEN course.school_title='SPPH' AND year>=2015 THEN 'SPPH' END) AS SPPH,
                COUNT(course_section.courseId) AS TOTAL
                FROM course_section, course
                WHERE semester = '$semester' AND year = '$year' AND course_section.courseId=course.courseId
                GROUP BY enrollment
                HAVING enrollment IS NOT NULL;";
                $result = $connection->query($sql);

                if (!$result){
                    die("Invalid query: " . $connection->error);
                }

                while($row = $result->fetch_assoc()){ echo "
                    <tr>
                      <td>$row[Enrollment]</td>
                      <td>$row[SBE]</td>
                      <td>$row[SELS]</td>
                      <td>$row[SETS]</td>
                      <td>$row[SLASS]</td>
                      <td>$row[SPPH]</td>
                      <td>$row[TOTAL]</td>
                    </tr>
                    "; 
                }

                foreach($result as $data){
                  $enrollment[] = $data['Enrollment'];
                  $sbe[] = $data['SBE'];
                  $sels[] = $data['SELS'];
                  $sets[] = $data['SETS'];
                  $slass[] = $data['SLASS'];
                  $spph[] = $data['SPPH'];
                  $total[] = $data['TOTAL'];
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


	<!-- JS for Charts -->
	<script>
		const ctx = document.getElementById('myChart');

    const labels = <?php echo json_encode($enrollment)?>;
    const data = {
    labels: labels,
    datasets: [
      {
        type: 'bar',
        label: 'SBE',
        data: <?php echo json_encode($sbe)?>,
      },
      {
        type: 'bar',
        label: 'SELS',
        data: <?php echo json_encode($sels)?>,
      },
      {
        type: 'bar',
        label: 'SETS',
        data: <?php echo json_encode($sets)?>,
      },
      {
        type: 'bar',
        label: 'SLASS',
        data: <?php echo json_encode($slass)?>,
      },
      {
        type: 'bar',
        label: 'SPPH',
        data: <?php echo json_encode($spph)?>,
      },
      {
        type: 'line',
        label: 'Total',
        data: <?php echo json_encode($total)?>,
      }
    ]
    };


    const config = {
    type: 'scatter',
    data: data,
    options: {
      responsive: true,
      plugins: {
        legend: {
          position: 'top',
        },
        title: {
          display: true,
          text: 'Class size distribution',
        }
      }
    }
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
