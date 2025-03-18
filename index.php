<!DOCTYPE html>
<html lang="en" data-bs-theme="light">

<head>

  <?php $title = 'Legis360 | Governance for everyone ';
  include 'partials/title-meta.php' ?>

  <?php include 'partials/head-css.php' ?>

</head>


<!-- Body -->

<body>

  <?php include 'partials/preloader.php' ?>


  <!-- Page wrapper for sticky footer -->
  <!-- Wraps everything except footer to push footer to the bottom of the page if there is little content -->
  <main class="page-wrapper">

    <?php $navClass = 'navbar-dark position-absolute navbar-sticky';
    include 'partials/navbar.php' ?>


    <!-- Hero section -->
    <section class="position-relative bg-dark pt-lg-4 pt-xl-5" style="background: linear-gradient(90deg, #0B0F19 0%, #172033 51.04%, #0B0F19 100%);">
      <div class="jarallax position-absolute top-0 start-0 w-100 h-100" data-jarallax data-speed="0.4">
        <div class="jarallax-img" style="background-image: url(assets/img/landing/saas-5/hero-bg-pattern.png);"></div>
      </div>
      <div class="container position-relative zindex-2 pt-2 pt-sm-4 pt-md-5">
        <div class="row justify-content-center pt-5">
          <div class="col-lg-9 col-xl-8 text-center pt-5 mt-1">
            <a href="#" class="d-inline-flex align-items-center fs-sm fw-semibold text-decoration-none border border-primary border-opacity-50 rounded-pill py-1 px-3">
              <span class="text-gradient-primary lh-lg">Legis360 v1.0</span>
              <i class="bx bx-right-arrow-alt text-gradient-primary fs-lg ms-2 me-n1"></i>
            </a>
            <h1 class="display-5 text-white pt-3 mt-3 mb-4">Transforming Civic Engagement in Africa Through AI-Powered Governance</h1>
            <p class="text-white opacity-70 fs-xl">Legis360 is an AI-powered platform designed making governance more transparent, participatory, and responsive.</p>
          </div>
        </div>
      </div>
      <div class="d-none d-lg-block" style="height: 480px;"></div>
      <div class="d-lg-none" style="height: 400px;"></div>
      <div class="d-flex position-absolute bottom-0 start-0 w-100 overflow-hidden mb-n1" style="color: var(--si-body-bg);">
        <div class="position-relative start-50 translate-middle-x flex-shrink-0" style="width: 3774px;">
          <svg width="3774" height="201" viewBox="0 0 3774 201" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M0 200.003C0 200.003 1137.52 0.188224 1873.5 0.000134392C2614.84 -0.189325 3774 200.003 3774 200.003H0Z" fill="currentColor" />
          </svg>
        </div>
      </div>
    </section>


    <!-- Categories (Slider) -->
    <section class="container position-relative zindex-3">
      <div class="d-none d-lg-block" style="margin-top: -428px;"></div>
      <div class=" d-lg-none" style="margin-top: -370px;"></div>
      <div class="swiper" data-swiper-options='{
          "slidesPerView": 1,
          "spaceBetween": 24,
          "pagination": {
            "el": ".swiper-pagination",
            "clickable": true
          },
          "breakpoints": {
            "560": {
              "slidesPerView": 2
            },
            "960": {
              "slidesPerView": 3
            }
          }
        }'>
        <div class="swiper-wrapper">

          <!-- Item -->
          <div class="swiper-slide">
            <a href="#" class="card-portfolio position-relative d-block rounded-3 overflow-hidden">
              <span class="position-absolute top-0 start-0 w-100 h-100 zindex-1" style="background: linear-gradient(180deg, rgba(17, 24, 39, 0.00) 35.56%, #111827 95.3%);"></span>
              <div class="position-absolute bottom-0 w-100 zindex-2 p-4">
                <div class="px-md-3">
                  <h3 class="h4 text-white mb-0">Citizens</h3>
                  <div class="card-portfolio-meta d-flex align-items-center justify-content-between">
                    <span class="text-white fs-xs text-truncate opacity-70 pe-3">Track legislation and engage in governance</span>
                    <i class="bx bx-right-arrow-circle fs-3 text-gradient-primary"></i>
                  </div>
                </div>
              </div>
              <div class="card-img">
                <img src="assets/img/citizens.png" alt="Citizens">
              </div>
            </a>
          </div>

          <!-- Item -->
          <div class="swiper-slide">
            <a href="#" class="card-portfolio position-relative d-block rounded-3 overflow-hidden">
              <span class="position-absolute top-0 start-0 w-100 h-100 zindex-1" style="background: linear-gradient(180deg, rgba(17, 24, 39, 0.00) 35.56%, #111827 95.3%);"></span>
              <div class="position-absolute bottom-0 w-100 zindex-2 p-4">
                <div class="px-3">
                  <h3 class="h4 text-white mb-0">Legislators & Policymakers</h3>
                  <div class="card-portfolio-meta d-flex align-items-center justify-content-between">
                    <span class="text-white fs-xs text-truncate opacity-70 pe-3">Enhancing Government Transparency</span>
                    <i class="bx bx-right-arrow-circle fs-3 text-gradient-primary"></i>
                  </div>
                </div>
              </div>
              <div class="card-img">
                <img src="assets/img/legislators.png" alt="legislators">
              </div>
            </a>
          </div>

          <!-- Item -->
          <div class="swiper-slide">
            <a href="#" class="card-portfolio position-relative d-block rounded-3 overflow-hidden">
              <span class="position-absolute top-0 start-0 w-100 h-100 zindex-1" style="background: linear-gradient(180deg, rgba(17, 24, 39, 0.00) 35.56%, #111827 95.3%);"></span>
              <div class="position-absolute bottom-0 w-100 zindex-2 p-4">
                <div class="px-md-3">
                  <h3 class="h4 text-white mb-0">Civic Activists</h3>
                  <div class="card-portfolio-meta d-flex align-items-center justify-content-between">
                    <span class="text-white fs-xs text-truncate opacity-70 pe-3">Defend civic space, promote human rights, and influence policy</span>
                    <i class="bx bx-right-arrow-circle fs-3 text-gradient-primary"></i>
                  </div>
                </div>
              </div>
              <div class="card-img">
                <img src="assets/img/activities.png" alt="Activist">
              </div>
            </a>
          </div>

          <!-- Item -->
          <div class="swiper-slide">
            <a href="#" class="card-portfolio position-relative d-block rounded-3 overflow-hidden">
              <span class="position-absolute top-0 start-0 w-100 h-100 zindex-1" style="background: linear-gradient(180deg, rgba(17, 24, 39, 0.00) 35.56%, #111827 95.3%);"></span>
              <div class="position-absolute bottom-0 w-100 zindex-2 p-4">
                <div class="px-md-3">
                  <h3 class="h4 text-white mb-0">Journalists</h3>
                  <div class="card-portfolio-meta d-flex align-items-center justify-content-between">
                    <span class="text-white fs-xs text-truncate opacity-70 pe-3">Access real-time legislative data.</span>
                    <i class="bx bx-right-arrow-circle fs-3 text-gradient-primary"></i>
                  </div>
                </div>
              </div>
              <div class="card-img">
                <img src="assets/img/journalist.png" alt="Journalists">
              </div>
            </a>
          </div>

          <!-- Item -->
          <div class="swiper-slide">
            <a href="#" class="card-portfolio position-relative d-block rounded-3 overflow-hidden">
              <span class="position-absolute top-0 start-0 w-100 h-100 zindex-1" style="background: linear-gradient(180deg, rgba(17, 24, 39, 0.00) 35.56%, #111827 95.3%);"></span>
              <div class="position-absolute bottom-0 w-100 zindex-2 p-4">
                <div class="px-3">
                  <h3 class="h4 text-white mb-0">Researchers</h3>
                  <div class="card-portfolio-meta d-flex align-items-center justify-content-between">
                    <span class="text-white fs-xs text-truncate opacity-70 pe-3">Access historical legislative data.</span>
                    <i class="bx bx-right-arrow-circle fs-3 text-gradient-primary"></i>
                  </div>
                </div>
              </div>
              <div class="card-img">
                <img src="assets/img/researchers.png" alt="Researchers">
              </div>
            </a>
          </div>
        </div>

        <!-- Pagination (bullets) -->
        <div class="swiper-pagination position-relative bottom-0 pt-2 pt-md-3 mt-4"></div>
      </div>
    </section>


    <!-- Features -->
    <section class="container py-5 my-md-3 my-lg-4 my-xl-5">
      <div class="row align-items-center align-items-xl-end py-2 pb-sm-3">
        <div class="col-md-6 mb-5 mb-md-0">
          <div style="max-width: 570px;">
            <h2 class="h1 pb-3 mb-2 mb-md-3">What you can achieve with Legis360</h2>
            <div class="row row-cols-1 row-cols-sm-2 gx-lg-5 g-4">
              <div class="col d-md-flex d-xl-block align-items-center pt-1 pt-sm-2 pt-md-0 pt-xl-3">
                <h3 class="h5 pb-sm-1 mb-2">
                  <span class="d-md-none d-xl-block">Empower advocacy and civic space</span>
                  <span class="fs-base text-nav d-none d-md-block d-xl-none">Empower advocacy and civic space</span>
                </h3>
                <p class="fs-sm mb-0 d-md-none d-xl-block">By accessing historical records, monitoring policy trends, and supporting democratic participation.</p>
              </div>
              <div class="col d-md-flex d-xl-block align-items-center pt-1 pt-sm-2 pt-md-0 pt-xl-3">
                <h3 class="h5 pb-sm-1 mb-2">
                  <span class="d-md-none d-xl-block">Engage directly with legislators</span>
                  <span class="fs-base text-nav d-none d-md-block d-xl-none">Engage directly with legislators</span>
                </h3>
                <p class="fs-sm mb-0 d-md-none d-xl-block">Send messages, track responses, and participate in transparent dialogue with policymakers.</p>
              </div>
              <div class="col d-md-flex d-xl-block align-items-center pt-1 pt-sm-2 pt-md-0 pt-xl-3">
                <h3 class="h5 pb-sm-1 mb-2">
                  <span class="d-md-none d-xl-block">Track and analyze legislation</span>
                  <span class="fs-base text-nav d-none d-md-block d-xl-none">Track and analyze legislation</span>
                </h3>
                <p class="fs-sm mb-0 d-md-none d-xl-block">Stay updated on bills, motions, and legislative activities with AI-generated summaries, Get real-time updates on committee activities, referenced bills, and inter-chamber collaborations.</p>
              </div>
              <div class="col d-md-flex d-xl-block align-items-center pt-1 pt-sm-2 pt-md-0 pt-xl-3">
                <h3 class="h5 pb-sm-1 mb-2">
                  <span class="d-md-none d-xl-block">Forum Discussion</span>
                  <span class="fs-base text-nav d-none d-md-block d-xl-none">Forum Discussion</span>
                </h3>
                <p class="fs-sm mb-0 d-md-none d-xl-block">Where voices shape policies—join the conversation, influence legislation!</p>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="rellax position-relative rounded-3 mb-xl-5" style="box-shadow: -7px 12px 60px 0 rgba(126,123,160, .12);" data-rellax-percentage="0.5" data-rellax-speed="1.3" data-disable-parallax-down="xl">
            <img src="assets/img/parliament-white.png" class="d-block rounded-3" alt="Stats screen">
          </div>
        </div>
      </div>
    </section>

    
    <!-- Map -->
    <section class="bg-dark py-5" data-bs-theme="dark">
      <div class="container pt-2 py-sm-3 py-md-4 py-lg-5 my-xxl-3">
        <div class="row pt-lg-2 pt-xl-3">
          <div class="col-md-6">
            <h2 class="h1 pe-xxl-5 me-xl-4 mb-md-0">Built<span class="text-warning"> for Everyone,</span> ensuring governance remains people-centered</h2>
          </div>
          <div class="col-md-6 col-xl-5 offset-xl-1">
            <p class="text-body fs-xl mb-0">Whether you’re a citizen following a policy that affects your community, an activist advocating for legislative reforms, or a policymaker seeking direct engagement, Legis360 is built to serve you.</p>
          </div>
        </div>
      </div>
    </section>


    <!-- Video -->
    <section class="position-relative py-5">
      <span class="position-absolute top-0 start-0 w-100 h-100 d-dark-mode-none" style="background: linear-gradient(360deg, #fff 5.39%, #f3f6ff 78.66%);"></span>
      <span class="position-absolute top-0 start-0 w-100 h-100 d-none d-dark-mode-block" style="background: linear-gradient(360deg, #0b0f19 5.39%, rgba(255, 255, 255, .04) 78.66%);"></span>
      <div class="container position-relative zindex-2 py-2 py-sm-3 py-sm-4 py-md-5">
        <div class="row align-items-center py-lg-2 py-xl-3 my-xl-1 my-xxl-3">
          <div class="col-md-5 col-xl-4">
            <h2 class="h1 pb-2 pb-sm-3 mb-md-0">Learn <span class="text-gradient-primary">how Legis360 works</span> in a nutshell. Watch a short introductory video</h2>
          </div>
          <div class="col-md-7 offset-xl-1">
            <div class="position-relative">
              <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center zindex-5">
                <a href="#" class="btn btn-video btn-icon btn-xl stretched-link bg-white" data-bs-toggle="video" aria-label="Play video">
                  <i class="bx bx-play"></i>
                </a>
              </div>
              <img src="assets/img/cover_vid.jpg" class="d-dark-mode-none rounded-3" alt="Video cover">
              <img src="assets/img/cover_vid.jpg" class="d-none d-dark-mode-block rounded-3" alt="Video cover">
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