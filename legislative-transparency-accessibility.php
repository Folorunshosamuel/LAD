<!DOCTYPE html>
<html lang="en" data-bs-theme="light">

<head>

  <?php $title = 'legis360 | Legislative Transparency & Accessibility';
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
          <li class="breadcrumb-item active" aria-current="page">Legislative Transparency & Accessibility</li>
        </ol>
      </nav>

      <div class="row row-cols-1 row-cols-md-2 g-0 pb-2">

        <!-- Image -->
        <div class="col order-md-2 position-relative bg-position-center bg-size-cover bg-repeat-0 zindex-2" style="background-image: url(assets/img/cover1.png); border-radius: .5rem .5rem .5rem 0;">
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
              <li class="breadcrumb-item active" aria-current="page">Legislative Transparency & Accessibility</li>
            </ol>
          </nav>

          <div class="bg-secondary rounded-3 p-4 p-lg-5 mt-n2 mt-md-0 me-md-n2">
            <div class="px-sm-3 px-xl-4 pt-4 py-md-3 py-lg-4 py-xl-5">
              <h1 class="pb-2 pb-xxl-3">Legislative Transparency & Accessibility</h1>
              <h5 class="pb-2 pb-xxl-3">From Hidden Policies to Public Power</h5>
              <p class="pb-2 mb-4 mb-xxl-5">For too long, legislation has been buried in complexity—hard to find, harder to understand. Citizens, journalists, and advocacy groups struggle to keep up with laws that shape their lives. But what if access to legislative information was as easy as a simple search?<br><br>Legis360 tears down the barriers to information, making laws, policies, and government decisions open and understandable. With AI-powered insights, we break through legal jargon, giving people clear, actionable summaries of bills and policy changes. <br><br>No more guesswork, just facts, accessible to everyone.</p>
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