
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
<div class="az-content-header">
    <div class="az-content-header-top">
      <div>
        <h5 class="az-content-title mg-b-5 mg-b-lg-8">Hi, welcome <?= $userName ?>!</h5>
        <p class="mg-b-0" style="color: #fff">Nigeria National Assembly - 10th Assembly</p>
      </div>
      <div class="az-dashboard-date">
        <div class="date">
          <div style="color: #fff"><?= $day1 ?></div>
          <div>
            <span style="color: #fff"><?= $monthYear ?></span>
            <span style="color: #fff"><?= $dayOfWeek1 ?></span>
          </div>
        </div>
      </div><!-- az-dashboard-date -->
    </div><!-- az-content-header-top -->
    <div class="az-column-landing">
        <div>
            <h1 class="az-header-title" style="font-size: 3rem">Legislative Analysis Dashboard</h1>
            <h5>Legislative Analysis Dashboard by Yiaga Africa's  Centre for Legislative Engagement is Nigeria’s foremost independent parliamentary monitoring tool and policy think tank that bridges the gap between people and parliament.</h5>
            <a href="#" class="btn btn-outline-indigo">Learn More</a>
        </div>
    </div><!-- az-column-signup-left -->
</div><!-- az-content-header -->
</body>
</html>
