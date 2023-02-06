
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
						<a href="#"  class="active">
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
        <h1 style="color: #3f2b96;">Enrollment Breakdown</h1>
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
	  </div>

    <div class="d-flex flex-row justify-content-around">
      <table class="table" style="width: 600px">
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

                $tempsql = "SELECT max(enrolled) as Max_enrolled From course_section where semester='$semester' and year= '$year';";
                $tempresult = $connection->query($tempsql);

                if (!$tempresult){
                  die("Invalid query: " . $connection->error);
                }
                
                foreach($tempresult as $data){
                  $m = (int)$data['Max_enrolled'];
                }
                $sql = "";
                for($i=0; $i<$m; $i++){
                  $sql = $sql."SELECT '$i' as Enrollment, COUNT(CASE WHEN c.school_title='SBE' THEN 'SBE' END) AS SBE, COUNT(CASE WHEN c.school_title='SELS' THEN 'SELS' END) AS SELS, COUNT(CASE WHEN c.school_title='SETS' THEN 'SBE' END) AS SETS, COUNT(CASE WHEN c.school_title='SLASS' THEN 'SLASS' END) AS SLASS, COUNT(CASE WHEN c.school_title='SPPH' AND year>=2015 THEN 'SPPH' END) AS SPPH, COUNT(cs.courseId) AS Total FROM course_section as cs, course as c WHERE semester = '$semester' AND year = '$year' AND cs.courseId=c.courseId AND cs.blocked IN ('-1', '0') AND enrolled='$i' UNION ";
                }
                $sql = $sql."SELECT 'Total' as Enrollment, COUNT(CASE WHEN c.school_title='SBE' THEN 'SBE' END) AS SBE, COUNT(CASE WHEN c.school_title='SELS' THEN 'SELS' END) AS SELS, COUNT(CASE WHEN c.school_title='SETS' THEN 'SBE' END) AS SETS, COUNT(CASE WHEN c.school_title='SLASS' THEN 'SLASS' END) AS SLASS, COUNT(CASE WHEN c.school_title='SPPH' AND year>=2015 THEN 'SPPH' END) AS SPPH, COUNT(cs.courseId) AS Total FROM course_section as cs, course as c WHERE semester = '$semester' AND year = '$year' AND cs.courseId=c.courseId AND cs.blocked IN ('-1', '0') ;";

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
                    <td>$row[Total]</td>
                  </tr>
                  ";
                }
              }
            }
          ?>
        </tbody>
      </table>
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
