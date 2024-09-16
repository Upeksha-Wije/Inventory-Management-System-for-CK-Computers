<?php
  // Include the file containing login function code segement
require_once 'common/functions.php';
require_once 'common/config.php';

  // Check connection
  /*if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
  }*/

  $month = date('m');
  $day = date('d');
  $year = date('Y');

  $today = $year . '-' . $month . '-' . $day;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Your Title Here</title>
  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
  <script type="text/javascript">
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
      var data = google.visualization.arrayToDataTable([
        ['Date', 'RPE rating'],
        <?php
          $sql = "SELECT * FROM rpe ORDER BY dot ASC;";
          $fire = mysqli_query($con, $sql);
          while ($result = mysqli_fetch_assoc($fire)) {
            echo "['" . $result['dot'] . "'," . $result['rpe'] . "],";
          }
        ?>
      ]);

      var options = {
        title: 'Rate of Percieved Exertion Daily',
        hAxis: { title: 'Date', titleTextStyle: { color: '#333' } },
        vAxis: { minValue: 0 }
      };

      var chart = new google.visualization.AreaChart(document.getElementById('chart_div'));
      chart.draw(data, options);
    }
  </script>
</head>
<body>

<form class="" action="insert.php" method="post">
  Training Intensity
  <input type="number" placeholder="1-10" name="TI">
  <br>
  Training Duration
  <input type="number" placeholder="in minutes" name="TD">
  <br>
  Date
  <input type="date" class="form-control" name="date" value="<?php echo $today; ?>">
  <br>
  <input type="submit" name="submit" value="Submit">
</form>

<form class="" action="" method="post">
  <input type="date" name="date1" placeholder="Start date">
  <input type="date" name="date2" value="<?php echo $today; ?>" placeholder="End date">
  <input type="submit" name="Chooseinterval" value="Choose interval">
</form>

<div id="chart_div" style="width: 100%; height: 500px;"></div>

</body>
</html>

<?php
  // Close the connection
  mysqli_close($con);
?>
