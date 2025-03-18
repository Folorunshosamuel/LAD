
<?php

// Set the current date values dynamically
$day1 = date('d'); // Day of the month (e.g., 20)
$day2 = date('d', strtotime('+7 days')); // Day of the month for a week ahead (e.g., 27)
$monthYear = date('M Y'); // Current month and year (e.g., OCT 2018)
$dayOfWeek1 = date('l'); // Current day of the week (e.g., Sunday)
$dayOfWeek2 = date('l', strtotime('+7 days')); // Day of the week for a week ahead (e.g., Monday)
?>

<!DOCTYPE html>
<html lang="en">
<body>
<div class="az-content-hor">
    <div class="az-content-header-topp">
      <div>
        <h5 class="az-content-title mg-b-5 mg-b-lg-8">Hi, <?= $userName ?>!</h5>
        <p class="mg-b-0" style="color: #fff;">The Green Chamber - 10th Assembly </p>
        <p class="mg-b-0" style="color: #fff;"><?= $dayOfWeek1 ?>, <?= $day1 ?> <?= $monthYear ?></p>
      </div>
    </div><!-- az-content-header-top -->
</div><!-- az-content-header -->
</body>
</html>
