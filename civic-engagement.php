<!DOCTYPE html>
<html lang="en" data-bs-theme="light">

<head>

  <?php $title = 'legis360 | Civic Engagement & Participation';
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
          <li class="breadcrumb-item active" aria-current="page">Civic Engagement & Participation</li>
        </ol>
      </nav>

      <div class="row row-cols-1 row-cols-md-2 g-0 pb-2">

        <!-- Image -->
        <div class="col order-md-2 position-relative bg-position-center bg-size-cover bg-repeat-0 zindex-2" style="background-image: url(assets/img/cover2.png); border-radius: .5rem .5rem .5rem 0;">
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
              <li class="breadcrumb-item active" aria-current="page">Civic Engagement & Participation</li>
            </ol>
          </nav>

          <div class="bg-secondary rounded-3 p-4 p-lg-5 mt-n2 mt-md-0 me-md-n2">
            <div class="px-sm-3 px-xl-4 pt-4 py-md-3 py-lg-4 py-xl-5">
              <h1 class="pb-2 pb-xxl-3">Civic Engagement & Participation</h1>
              <h5 class="pb-2 pb-xxl-3">Bridging the Gap Between People and Power</h5>
              <p class="pb-2 mb-4 mb-xxl-5">In many places, governance feels distant, decisions are made for the people, but without them. Messages to legislators go unanswered, and policies are crafted behind closed doors. What if citizens had a direct line to the decision-makers?<br><br>Legis360 is about turning passive observers into active participants in shaping laws. We provide a platform for real conversations, where citizens can raise concerns, advocacy groups can push for change, and policymakers can listen, engage, and act with transparency.<br><br>By opening the doors of governance, Legis360 ensures that policies aren’t just written in offices, they are shaped by the people they impact.</p>
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