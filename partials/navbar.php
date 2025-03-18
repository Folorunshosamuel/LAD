<header class="header navbar navbar-expand-lg <?php echo ($navClass) ?>">
    <div class="container px-3">
        <a href="index.php" class="navbar-brand pe-3">
            <img src="assets/img/logo.svg" width="47" alt="legis360">
            Legis360
        </a>
        <div id="navbarNav" class="offcanvas offcanvas-end">
            <div class="offcanvas-header border-bottom">
                <h5 class="offcanvas-title">Menu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-current="page">About</a>
                        <div class="dropdown-menu p-0">
                            <div class="d-lg-flex">
                                <div class="mega-dropdown-column pt-lg-3 pb-lg-4" style="--si-mega-dropdown-column-width: 15rem;">
                                    <ul class="list-unstyled mb-0">
                                        <!-- <li><a href="about.php" class="dropdown-item">Who we are</a></li> -->
                                        <li><a href="mission-vision.php" class="dropdown-item">Mission/Vision</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-current="page">Focus Area</a>
                        <div class="dropdown-menu p-0">
                            <div class="d-lg-flex">
                                <div class="mega-dropdown-column pt-lg-3 pb-lg-4" style="--si-mega-dropdown-column-width: 20rem;">
                                    <ul class="list-unstyled mb-0">
                                        <li><a href="legislative-transparency-accessibility.php" class="dropdown-item">Legislative Transparency & Accessibility</a></li>
                                        <li><a href="civic-engagement.php" class="dropdown-item">Civic Engagement & Participation</a></li>
                                        <li><a href="policy-governance.php" class="dropdown-item">Policy Accountability & Governance</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Resources</a>
                        <div class="dropdown-menu">
                            <div class="d-lg-flex pt-lg-3">
                                <div class="mega-dropdown-column">
                                    <h6 class="px-3 mb-2">Bills</h6>
                                    <ul class="list-unstyled mb-3">
                                        <li><a href="#" class="dropdown-item py-1">Senate Bills</a></li>
                                        <li><a href="#" class="dropdown-item py-1">House of Reps Bills</a></li>
                                        <li><a href="#" class="dropdown-item py-1">State Assembly Bills<span class="badge bg-success ms-2">New</span></a></a></li>
                                    </ul>
                                    <h6 class="px-3 mb-2">Motions</h6>
                                    <ul class="list-unstyled mb-3">
                                        <li><a href="#" class="dropdown-item py-1">Senate Motions</a></li>
                                        <li><a href="#" class="dropdown-item py-1">House of Reps Motions</a></li>
                                    </ul>
                                </div>
                                <div class="mega-dropdown-column">
                                    <h6 class="px-3 mb-2">Petitions</h6>
                                    <ul class="list-unstyled mb-3">
                                        <li><a href="#" class="dropdown-item py-1">Senate Petitions</a></li>
                                        <li><a href="#" class="dropdown-item py-1">House of Reps Petitions</a></li>
                                    </ul>
                                    <h6 class="px-3 mb-2">Others</h6>
                                    <ul class="list-unstyled mb-3">
                                        <li><a href="#" class="dropdown-item py-1">Votes & Proceedings</a></li>
                                        <li><a href="#" class="dropdown-item py-1">Order Paper</a></li>
                                        <li><a href="#" class="dropdown-item py-1">Notice Paper</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">Legis360 AI<span class="badge bg-success ms-2">Soon</span></a></a>
                    </li>                    
                </ul>
            </div>
            <div class="offcanvas-header border-top">
                <a href="app/signin.php" class="btn btn-primary w-100" target="_blank" rel="noopener">
                    <i class="bx bx-lock fs-4 lh-1 me-1"></i>
                    &nbsp;Login
                </a>
            </div>
        </div>
        <div class="form-check form-switch mode-switch pe-lg-1 ms-auto me-4" data-bs-toggle="mode">
            <input type="checkbox" class="form-check-input" id="theme-mode">
            <label class="form-check-label d-none d-sm-block" for="theme-mode">Light</label>
            <label class="form-check-label d-none d-sm-block" for="theme-mode">Dark</label>
        </div>
        <button type="button" class="navbar-toggler" data-bs-toggle="offcanvas" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <a href="app/signin.php" class="btn btn-primary btn-sm fs-sm rounded d-none d-lg-inline-flex" target="_blank" rel="noopener">
            <i class="bx bx-lock fs-5 lh-1 me-1"></i>
            &nbsp;Login
        </a>
    </div>
</header>