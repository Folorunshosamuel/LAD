<?php
// Include database connection
include 'db_connect.php';
include 'header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $title = $_POST['title'];
        $chamber = $_POST['chamber'];
        $sponsor_id = $_POST['sponsor_id'];
        $dateFiled = !empty($_POST['dateFiled']) ? $_POST['dateFiled'] : null;
        $resolution = !empty($_POST['resolution']) ? $_POST['resolution'] : null;
        $committeeReferred = !empty($_POST['committeeReferred']) ? $_POST['committeeReferred'] : null;
        $committeeReportDate = !empty($_POST['committeeReportDate']) ? $_POST['committeeReportDate'] : null;

    // Handle file upload
    $motionFile = null;
    if (!empty($_FILES['motionFile']['name'])) {
        $motionFile = 'motions/' . basename($_FILES['motionFile']['name']);
        move_uploaded_file($_FILES['motionFile']['tmp_name'], $motionFile);
    }

    // Insert the new bill into the database
    $insertQuery = $db->prepare("INSERT INTO Motions (title, chamber, sponsor_id, dateFiled, resolution, committeeReferred, committeeReportDate, motionFile) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $insertQuery->execute([$title, $chamber, $sponsor_id, $dateFiled, $resolution, $committeeReferred, $committeeReportDate, $motionFile]);

    echo "<p class='success-message'>Motion added successfully!</p>";
}

// Fetch sponsors for the dropdown
$sponsorQuery = $db->query("SELECT id, name FROM legislators"); // Adjust for `horMembers` if needed
$sponsors = $sponsorQuery->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Motion</title>
    <link rel="stylesheet" href="lad.css"> <!-- Adjust path to your CSS file -->

    <style>
        .add-bill-container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .add-bill-container h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .form-group input[type="text"],
        .form-group input[type="date"],
        .form-group textarea,
        .form-group {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }
        .form-group textarea {
            resize: vertical;
            min-height: 100px;
        }
        .form-group input[type="file"] {
            font-size: 16px;
        }
        .success-message {
            text-align: center;
            color: green;
            font-weight: bold;
            margin-bottom: 15px;
        }
        .submit-btn {
            display: block;
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }
        .submit-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <?php
    include 'header.php'; // Include the header at the beginning of the dashboard
    ?>
    <div class="add-bill-container">
        <h2>Add New Motion</h2>

        <form action="" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" name="title" id="title" required>
            </div>

            <div class="form-group">
                <label for="chamber">Chamber</label>
                <select class="form-control select2" name="chamber" id="chamber" required>
                    <option value="">Select Chamber</option>
                    <option value="Senate">Senate</option>
                    <option value="House of Reps">House of Reps</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="sponsor_id">Sponsor</label>
                <select class="form-control select2" name="sponsor_id" id="sponsor_id" required>
                    <option value="">Select a sponsor</option>
                    <?php foreach ($sponsors as $sponsor): ?>
                        <option value="<?= htmlspecialchars($sponsor['id']) ?>"><?= htmlspecialchars($sponsor['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="firstReading">Date Moved</label>
                <input type="date" name="dateFiled" id="dateFiled">
            </div>

            <div class="form-group">
                <label for="resolution">Resolution</label>
                <input type="text" name="resolution" id="resolution">
            </div>

            <div class="form-group">
                <label for="committeeReferred">committee Referred to</label>
                <input type="text" name="committeeReferred" id="committeeReferred">
            </div>

            <div class="form-group">
                <label for="committeeReportDate">Committee Report Date</label>
                <input type="date" name="committeeReportDate" id="committeeReportDate">
            </div>

            <div class="form-group">
                <label for="motionFile">Motion Document</label>
                <input type="file" name="motionFile" id="motionFile" required>
            </div>

            <button type="submit" class="submit-btn">Add Motion</button>
        </form>
    </div>

    <script src="../lib/select2/js/select2.min.js"></script>

    <script>
      // Additional code for adding placeholder in search box of select2
      (function($) {
        var Defaults = $.fn.select2.amd.require('select2/defaults');

        $.extend(Defaults.defaults, {
          searchInputPlaceholder: ''
        });

        var SearchDropdown = $.fn.select2.amd.require('select2/dropdown/search');

        var _renderSearchDropdown = SearchDropdown.prototype.render;

        SearchDropdown.prototype.render = function(decorated) {

          // invoke parent method
          var $rendered = _renderSearchDropdown.apply(this, Array.prototype.slice.apply(arguments));

          this.$search.attr('placeholder', this.options.get('searchInputPlaceholder'));

          return $rendered;
        };

      })(window.jQuery);
    </script>

    <script>
      $(function(){
        'use strict'

        $(document).ready(function(){
          $('.select2').select2({
            placeholder: 'Choose one',
            searchInputPlaceholder: 'Search'
          });

          $('.select2-no-search').select2({
            minimumResultsForSearch: Infinity,
            placeholder: 'Choose one'
          });
        });

        $('.rangeslider1').ionRangeSlider();

        $('.rangeslider2').ionRangeSlider({
            min: 100,
            max: 1000,
            from: 550
        });

        $('.rangeslider3').ionRangeSlider({
            type: 'double',
            grid: true,
            min: 0,
            max: 1000,
            from: 200,
            to: 800,
            prefix: '$'
        });

        $('.rangeslider4').ionRangeSlider({
            type: 'double',
            grid: true,
            min: -1000,
            max: 1000,
            from: -500,
            to: 500,
            step: 250
        });

      });
    </script>
</body>
</html>
