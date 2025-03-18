<!DOCTYPE html>
<html lang="en" data-bs-theme="light">

<head>

  <?php $title = 'Legis360 | Policy Accountability & Governance';
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

    <!-- Hero -->
    <section class="container pt-4 pb-5 mb-lg-5">

      <!-- Breadcrumb mobile -->
      <nav class="d-md-none pb-3 mb-2 mb-lg-3" aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
          <li class="breadcrumb-item">
            <a href="index.php"><i class="bx bx-home-alt fs-lg me-1"></i>Home</a>
          </li>
          <li class="breadcrumb-item">
            <a href="#">Focus</a>
          </li>
          <li class="breadcrumb-item active" aria-current="page">Policy Accountability & Governance</li>
        </ol>
      </nav>

      <div class="row row-cols-1 row-cols-md-2 g-0 pb-2">

        <!-- Image -->
        <div class="col order-md-2 position-relative bg-position-center bg-size-cover bg-repeat-0 zindex-2" style="background-image: url(assets/img/cover3.png); border-radius: .5rem .5rem .5rem 0;">
          <div style="height: 250px;"></div>
        </div>

        <!-- Text + Breadcrumb desktop -->
        <div class="col order-md-1">
          <nav class="d-none d-md-block py-3 mb-2 mb-lg-3" aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
              <li class="breadcrumb-item">
                <a href="index.php"><i class="bx bx-home-alt fs-lg me-1"></i>Home</a>
              </li>
              <li class="breadcrumb-item">
                <a href="#">Focus</a>
              </li>
              <li class="breadcrumb-item active" aria-current="page">Policy Accountability & Governance</li>
            </ol>
          </nav>

          <div class="bg-secondary rounded-3 p-4 p-lg-5 mt-n2 mt-md-0 me-md-n2">
            <div class="px-sm-3 px-xl-4 pt-4 py-md-3 py-lg-4 py-xl-5">
              <h1 class="pb-2 pb-xxl-3">Policy Accountability & Governance</h1>
              <h5 class="pb-2 pb-xxl-3">Turning Promises into Progress</h5>
              <p class="pb-2 mb-4 mb-xxl-5">Elections come and go, but how often do politicians follow through on their commitments? Committees hold meetings, but do they lead to real action? For democracy to thrive, governance must be more than words, it must be measurable, trackable, and accountable.<br><br>Legis360 ensures that leaders don’t just make promises—they deliver. By keeping records of past actions, committee discussions, and legislative decisions, we help citizens, journalists, and advocacy groups track what’s really happening behind the scenes.<br><br>Accountability is not just about observing, it’s about ensuring better governance for all that's why with Legis360, we move from blind trust to informed oversight.</p>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>

  <?php include 'partials/footer.php' ?>
  <?php include 'partials/back-to-top.php' ?>

  <?php include 'partials/footer-script.php' ?>
</body>

</html>