
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
					<h2 class="mb-0"><img src="logo.png"> IUB</h2>
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
						<a href="" class="active">
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
	</div>


	<!--Content Start-->
	<div class="content-start transition">
		<div class="d-flex flex-column align-items-center">
        <h1 style="color: #3f2b96;">Resource Summary</h1>
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
          <th scope="col"></th>
          <th scope="col">Sum</th>
          <th scope="col">Avg Enroll</th>
          <th scope="col">Avg Room</th>
          <th scope="col">Difference</th>
          <th scope="col">Unused%</th>
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
          WHEN semester = 'Spring' THEN 'Spring'
          WHEN semester = 'Summer' THEN 'Summer'
          WHEN semester = 'Autumn' THEN 'Autumn'
      END AS 'School',
      SUM(enrolled) AS 'Sum',
      SUM(enrolled)/COUNT(course_section.courseId) AS 'Avg_Enroll',
      SUM(classroom.room_capacity)/COUNT(course_section.courseId) AS 'Avg_Room',
      (SUM(classroom.room_capacity)/COUNT(course_section.courseId) - SUM(enrolled)/COUNT(course_section.courseId)) AS 'Difference',
      ((SUM(classroom.room_capacity)/COUNT(course_section.courseId) - SUM(enrolled)/COUNT(course_section.courseId))/(SUM(classroom.room_capacity)/COUNT(course_section.courseId)))*100 AS 'Unused'
      FROM course_section, classroom, course
      WHERE semester = '$semester'
      AND year = '$year'
      AND course_section.roomId = classroom.roomId 
      AND course_section.courseId = course.courseId AND course_section.blocked!='B-0'
      UNION
      SELECT 
      CASE
      WHEN course.school_title='SBE' then 'SBE'
      WHEN course.school_title='SELS' then 'SELS'
      WHEN course.school_title='SETS' then 'SETS'
      WHEN course.school_title='SLASS' then 'SLASS'
      WHEN course.school_title='SPPH' then 'SPPH'
      END AS 'School',
      SUM(enrolled) AS 'Sum',
      SUM(enrolled)/COUNT(course_section.courseId) AS 'Avg_Enroll',
      SUM(classroom.room_capacity)/COUNT(course_section.courseId) AS 'Avg_Room',
      (SUM(classroom.room_capacity)/COUNT(course_section.courseId) - SUM(enrolled)/COUNT(course_section.courseId)) AS 'Difference',
      ((SUM(classroom.room_capacity)/COUNT(course_section.courseId) - SUM(enrolled)/COUNT(course_section.courseId))/(SUM(classroom.room_capacity)/COUNT(course_section.courseId)))*100 AS 'Unused'
      FROM course_section, classroom, course
      WHERE semester = '$semester' 
      AND year = '$year' 
      AND course_section.roomId = classroom.roomId 
      AND course_section.courseId = course.courseId AND course_section.blocked!='B-0'
      GROUP BY School;";
    $result = $connection->query($sql);

    if (!$result){
        die("Invalid query: " . $connection->error);
    }


    while($row = $result->fetch_assoc()){ echo "
        <tr>
          <td>$row[School]</td>
          <td>$row[Sum]</td>
          <td>$row[Avg_Enroll]</td>
          <td>$row[Avg_Room]</td>
          <td>$row[Difference]</td>
          <td>$row[Unused]</td>
        </tr>
        "; }
      }
      }
?>
      </tbody>
    </table>

    <table class="table" style="width: 400px">
      <thead>
        <tr>
          <th scope="col"></th>
          <th scope="col">Semester</th>
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
  else if($semester=='Summer' && $year=='2020'){
    echo "No Data";
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

    $sql = "SELECT 'Average of ROOM_CAPACITY' as 'Catagory', SUM(classroom.room_capacity)/COUNT(course_section.courseId) as Semester FROM course_section, course, classroom WHERE semester= '$semester' AND year = '$year' AND course_section.courseId=course.courseId AND course_section.roomId=classroom.roomId AND course_section.blocked!='B-' 
    UNION 
    SELECT 'Average of ENROLLED', SUM(enrolled)/COUNT(course_section.courseId) as 'Avg Enrolled' FROM course_section, course, classroom WHERE semester= '$semester' AND year = '$year' AND course_section.courseId=course.courseId AND course_section.roomId=classroom.roomId AND course_section.blocked!='B-'
    UNION 
    SELECT 'Average of Unused Space', (SUM(classroom.room_capacity)/COUNT(course_section.courseId) - SUM(enrolled)/COUNT(course_section.courseId)) as 'Avg Unused Space' FROM course_section, course, classroom WHERE semester= '$semester' AND year = '$year' AND course_section.courseId=course.courseId AND course_section.roomId=classroom.roomId AND course_section.blocked!='B-' 
    UNION
    SELECT 'Unused Percent', ((SUM(classroom.room_capacity)/COUNT(course_section.courseId) - SUM(enrolled)/COUNT(course_section.courseId))/(SUM(classroom.room_capacity)/COUNT(course_section.courseId)))*100 as 'Unsed%' FROM course_section, course, classroom
    WHERE semester= '$semester' AND year = '$year' AND course_section.courseId=course.courseId AND course_section.roomId=classroom.roomId AND course_section.blocked!='B-';";
    $result = $connection->query($sql);

    if (!$result){
        die("Invalid query: " . $connection->error);
    }


    while($row = $result->fetch_assoc()){ echo "
        <tr>
          <td>$row[Catagory]</td>
          <td>$row[Semester]</td>
        </tr>
        "; }
      }
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
