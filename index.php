<?php
// Include database connection and lad.css stylesheet
include 'db_connect.php';
echo '<link rel="stylesheet" href="lad.css">';

// Retrieve data for summary cards
$totalMembers = $db->query("SELECT COUNT(*) FROM legislators")->fetchColumn();
$totalSenate = $db->query("SELECT COUNT(*) FROM senateMembers")->fetchColumn();
$totalReps = $db->query("SELECT COUNT(*) FROM horMembers")->fetchColumn();
$totalFemale = $db->query("SELECT COUNT(*) FROM legislators WHERE gender = ' Female'")->fetchColumn();
$femalePercentage = ($totalMembers > 0) ? round(($totalFemale / $totalMembers) * 100, 2) : 0;
$totalMale = $db->query("SELECT COUNT(*) FROM legislators WHERE gender = 'Male'")->fetchColumn();
$malePercentage = ($totalMembers > 0) ? round(($totalMale / $totalMembers) * 100, 2) : 0;
$totalYoungMembers = $db->query("SELECT COUNT(*) FROM horMembers WHERE TIMESTAMPDIFF(YEAR, dob, CURDATE()) < 40")->fetchColumn();
$youngPercentage = ($totalMembers > 0) ? round(($totalYoungMembers / $totalMembers) * 100, 2) : 0;


// Retrieve recent bills for the activity card
$recentBills = $db->query("SELECT id, billNum, title, firstReading FROM Bills ORDER BY firstReading DESC LIMIT 5")->fetchAll();

// Retrieve all members for the members table
$members = $db->query("SELECT id, name, gender, dob, state, constituency, position FROM horMembers")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Legislative Analysis dashboard - Welcome</title>
    <link rel="stylesheet" href="lad.css"> <!-- Adjust path to your CSS file -->
    <style>
         .card-summary { display: flex; align-items: normal; justify-content: space-between; }
        .icon-container {
            width: 50px; height: 50px;
            display: flex; align-items: center; justify-content: center;
            border-radius: 50%; color: #fff; font-size: 1.5rem;
        }
        .note-red { color: red; }
        .activity-card, .members-table { margin-top: 20px; }
    </style>
</head>
<body>
    <?php
    include 'header.php'; // Include the header at the beginning of the dashboard
    ?>
    <?php
    include 'boilerplate.php'; // Include the boilerplate after the header
    ?>

    <main>
    <div class="container">
            <section class="py-5 mb-5">
                <div class="row">
                    <div class="col-md-4">
                        <div class="feature-icon feature-icon-lg rounded border text-primary mb-40px">
                            <i class="typcn icon typcn-coffee tx-26"></i>
                        </div>
                        <h5 class="font-weight-bold">For Citizens</h5>
                        <p class="text-muted mb-20px">Stay informed with real-time updates on legislative activities. Access bills, motions, and petitions with just a click.</p>
                        <a href="#!" class="text-dark font-weight-bold">Learn more</a>
                    </div>
                    <div class="col-md-4">
                        <div class="feature-icon feature-icon-lg rounded border text-primary mb-40px">
                            <i class="typcn icon typcn-briefcase tx-26"></i>
                        </div>
                        <h5 class="font-weight-bold">For Legislators</h5>
                        <p class="text-muted mb-20px">Your work made accessible. Showcase your activities, respond to messages, and connect with your constituents seamlessly.</p>
                        <a href="#!" class="text-dark font-weight-bold">Learn more</a>
                    </div>
                    <div class="col-md-4">
                        <div class="feature-icon feature-icon-lg rounded border text-primary mb-40px">
                            <i class="typcn icon typcn-folder-add tx-26"></i>
                        </div>
                        <h5 class="font-weight-bold">Data Insights</h5>
                        <p class="text-muted mb-20px">Analyze legislative data for smarter decisions. Visualize trends, gender representation, and party distribution at a glance.</p>
                        <a href="#!" class="text-dark font-weight-bold">Learn more</a>
                    </div>
                </div>
            </section>
            <section class="lead-landing-section bg-gradient bg-5">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <img src="img_5.png" width="535px" alt="analytics" class="img-fluid">
                    </div>
                    <div class="col-md-6">
                        <h6 class="font-weight-bold text-primary mb-20px mt-3">About</h6>
                        <h2 class="font-weight-bold mb-20px">
                        Empowering Legislative <br> Transparency.
                        </h2>
                        <p class="text-muted mb-20px">Engage directly with lawmakers. Share your thoughts, submit petitions, and stay involved in the legislative process.</p>
                        <ul class="list-ckeck-soft mb-30px">
                            <li>Perfect for modern startups</li>
                            <li>Ready to be customized</li>
                        </ul>
                        <button class="btn btn-primary">Learn more</button>
                    </div>
                </div>
            </div>
        </section>
        <section class="lead-landing-section mb-5">
            <div class="container">
                <h6 class="text-primary font-weight-bold">Testimonial</h6>
                <div id="landing-sass-testimonial-carousel" class="landing-sass-testimonial-carousel carousel slide" data-ride="carousel">
                    <div class="d-flex mb-5">
                        <h2 class="font-weight-bold mb-0">what people say about us</h2>
                    </div>
                    
                    <div class="carousel-inner" role="listbox">
                        <div class="carousel-item active">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="card">
                                        <div class="card-body p-40px">
                                            <h5>Transformative!</h5>
                                            <p class="text-muted">The Legislative Analysis Dashboard has completely transformed how I engage with our lawmakers. The ability to track bills, motions, and petitions in real-time is a game-changer. It’s a one-stop solution for civic engagement!</p>
                                            <div class="media">
                                                <div class="avatar avatar-rounded avatar-sm me-3">
                                                    <img src="#" alt="avatar">
                                                </div>
                                                <div class="media-body">
                                                    <h6 class="mb-0">Ademola T</h6>
                                                    <p class="text-muted mb-0">Policy Analyst</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="card">
                                        <div class="card-body p-40px">
                                            <h5>Effective Engagement</h5>
                                            <p class="text-muted">As a legislator, managing my communications used to be chaotic. With the streamlined messaging system, I can now efficiently respond to my constituents and track ongoing discussions. This platform has truly simplified my work.</p>
                                            <div class="media">
                                                <div class="avatar avatar-rounded avatar-sm me-3">
                                                    <img src="" alt="avatar">
                                                </div>
                                                <div class="media-body">
                                                    <h6 class="mb-0">Hon. Grace E.</h6>
                                                    <p class="text-muted mb-0">Member of House of Reps</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="card">
                                        <div class="card-body p-40px">
                                            <h5>Transparency at it best!</h5>
                                            <p class="text-muted">Transparency in governance is crucial, and this platform delivers on that promise. The AI-powered bill analyzer makes it easy to understand legislative content, even for non-experts like me. I highly recommend it to anyone interested in Nigerian politics.</p>
                                            <div class="media">
                                                <div class="avatar avatar-rounded avatar-sm me-3">
                                                    <img src="" alt="avatar">
                                                </div>
                                                <div class="media-body">
                                                    <h6 class="mb-0">Chinwe O.</h6>
                                                    <p class="text-muted mb-0">Civic Advocate</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="card">
                                        <div class="card-body p-40px">
                                            <h5>Improved our work flow</h5>
                                            <p class="text-muted">I was initially skeptical, but the dashboard has proven to be invaluable for our NGO's advocacy work. From accessing detailed legislative data to analyzing trends, it has helped us align our campaigns with policy priorities.</p>
                                            <div class="media">
                                                <div class="avatar avatar-rounded avatar-sm me-3">
                                                    <img src="" alt="avatar">
                                                </div>
                                                <div class="media-body">
                                                    <h6 class="mb-0">Ngozi A</h6>
                                                    <p class="text-muted mb-0">Program Director, Women for Justice</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="py-5 mb-5">
            <div class="container">
                <h3 class="text-center mb-40px">Join the 200+ organisation and institutions using the our platform</h3>
                <div class="row">
                    <div class="col-sm-3 mb-20px px-3">
                        <div class="card h-100 d-flex align-items-center justify-content-center p-3 rounded-0">
                            <div class="card-body">
                                <img src="uploads/SenateLogoImage.png" alt="client" class="img-fluid">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3 mb-20px px-3">
                        <div class="card h-100 d-flex align-items-center justify-content-center p-3 rounded-0">
                            <div class="card-body">
                                <img src="uploads/HORLogoImage.png" alt="client" class="img-fluid">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3 mb-20px px-3">
                        <div class="card h-100 d-flex align-items-center justify-content-center p-3 rounded-0">
                            <div class="card-body">
                                <img src="uploads/yiaga.png" alt="client" class="img-fluid">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3 mb-20px px-3">
                        <div class="card h-100 d-flex align-items-center justify-content-center p-3 rounded-0">
                            <div class="card-body">
                                <img src="uploads/eulogo.jpg" alt="client" class="img-fluid">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        </div> 
    </main>
    <?php
    include 'footer.php';
    ?>

    <!-- Scripts -->
    <script>
        $(document).ready(function() {
            $('#membersTable').DataTable();
        });
    </script>
    <script>
            // Initialize Nigeria Map
            $('#nigeria-map').vectorMap({
                map: 'nigeria_en',
                backgroundColor: '#f4f4f4',
                color: '#333333',
                hoverOpacity: 0.7,
                selectedColor: '#666666',
                enableZoom: true,
                showTooltip: true,
                values: <?php /* Insert state-specific data here as a JavaScript object */ ?>,
                scaleColors: ['#C8EEFF', '#006491'],
                normalizeFunction: 'polynomial'
            });
        });
    </script>
    <script>
      $(function(){
        'use strict'

      });
    </script> 
</body>
</html>
