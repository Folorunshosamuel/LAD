<!DOCTYPE html>
<html lang="en" data-bs-theme="light">

<head>

  <?php $title = 'Legis360 | Mission and Vision ';
  include 'partials/title-meta.php' ?>

  <?php include 'partials/head-css.php' ?>

</head>


<!-- Body -->

<body>

  <?php include 'partials/preloader.php' ?>


  <!-- Page wrapper for sticky footer -->
  <!-- Wraps everything except footer to push footer to the bottom of the page if there is little content -->
  <main class="page-wrapper">


    <?php $navClass = 'bg-light navbar-sticky';
    include 'partials/navbar.php' ?>

    <!-- Breadcrumb -->
    <nav class="container py-4 mb-md-2 mb-lg-5 mt-lg-3" aria-label="breadcrumb">
      <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item">
          <a href="index.php"><i class="bx bx-home-alt fs-lg me-1"></i>Home</a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">Mission & Vision</li>
      </ol>
    </nav>


    <!-- Page title -->
    <section class="container pb-5 mb-md-2 mb-lg-4 mb-xl-5">
      <div class="row pb-5 mb-md-2 mb-lg-4 mb-xl-5">
        <div class="col-lg-6">
          <h3 class="display-4 mb-0">Empowering Africa Through Legislative Transparency</h3>
        </div>
        <div class="col-lg-6 col-xl-5 offset-xl-1 pt-3 pt-sm-4 pt-lg-3">
          <p class="fs-xl pb-4 mb-1 mb-md-2 mb-xl-3">Legis360 is an AI-driven platform revolutionizing legislative engagement across Africa. We bridge the gap between citizens and governance by making laws, policies, and decision-making processes more accessible, transparent, and participatory.</p>
        </div>
      </div>
      <hr>
    </section>
    <!-- Image + Text sections -->
    <section class="container pb-sm-1 pb-md-3">

      <!-- Row 1 -->
      <div class="row align-items-lg-center pb-5 mb-2 mb-lg-4 mb-xl-5">
        <div class="col-md-6 mb-4 mb-md-0">
          <img src="assets/img/Africa-power.jpg" class="rounded-3" alt="Image">
        </div>
        <div class="col-md-6">
          <div class="ps-xl-5 ms-md-2 ms-lg-4">
            <h2 class="h1 mb-3 mb-sm-4">Our Mission</h2>
            <p class="mb-4 mb-lg-5">At Legis360, our mission is to empower African citizens, policymakers, and advocacy groups with technology-driven solutions that enhance transparency, participation, and accountability in governance. We believe that legislation should be accessible to all, and we are committed to using AI and data science to bridge the gap between the people and their governments. By doing so, we foster a culture of informed civic engagement, stronger institutions, and democratic resilience across Africa.</p>
            <a href="signup.php" class="btn btn-lg btn-primary shadow-primary">Join us on this Journey</a>
          </div>
        </div>
      </div>

      <!-- Row 2 -->
      <div class="row align-items-lg-center pt-md-3 pb-5 mb-2 mb-lg-4 mb-xl-5">
        <div class="col-md-6 order-md-2 mb-4 mb-md-0">
          <img src="assets/img/soro-soke-lg.jpg" class="rounded-3" alt="Image">
        </div>
        <div class="col-md-6 order-md-1">
          <div class="pe-xl-5 me-md-2 me-lg-4">
            <h2 class="h1 mb-3 mb-sm-4">Our Vision</h2>
            <p class="mb-3 mb-sm-4">We envision an Africa where every citizen has the power to engage with legislative processes effortlessly—where laws, policies, and governance decisions are open, understandable, and shaped by the voices of the people. Through Legis360, we aim to redefine legislative engagement across the continent, ensuring that governance is inclusive, participatory, and truly representative of Africa’s diverse communities.</p>
          </div>
        </div>
      </div>
    </section>


    <!-- CTA -->
    <section class="position-relative bg-dark py-5">
      <span class="position-absolute top-0 start-0 w-100 h-100" style="background-color: rgba(255, 255, 255, .05)"></span>
      <div class="container position-relative zindex-5 text-center my-xl-3 py-1 py-md-4 py-lg-5">
        <p class="lead text-light opacity-70 mb-3">Ready to get started?</p>
        <h2 class="h1 text-light pb-3 pb-lg-0 mb-lg-5">Track the legislations that affect you</h2>
        <a href="signup.php" class="btn btn-primary shadow-primary btn-lg">Sign up today!</a>
      </div>
    </section>
  </main>


  <?php include 'partials/footer.php' ?>

  <?php include 'partials/back-to-top.php' ?>

  <?php include 'partials/footer-script.php' ?>
</body>

</html>