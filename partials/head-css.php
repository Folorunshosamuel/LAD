 <!-- Vendor Styles -->
 <link rel="stylesheet" href="assets/css/vendors.min.css">

 <!-- Main Theme Styles + Bootstrap -->
 <link rel="stylesheet" media="screen" href="assets/css/theme.min.css">

 <!-- Page loading styles -->
 <style>
     .page-loading {
         position: fixed;
         top: 0;
         right: 0;
         bottom: 0;
         left: 0;
         width: 100%;
         height: 100%;
         -webkit-transition: all .4s .2s ease-in-out;
         transition: all .4s .2s ease-in-out;
         background-color: #fff;
         opacity: 0;
         visibility: hidden;
         z-index: 9999;
     }

     [data-bs-theme="dark"] .page-loading {
         background-color: #0b0f19;
     }

     .page-loading.active {
         opacity: 1;
         visibility: visible;
     }

     .page-loading-inner {
         position: absolute;
         top: 50%;
         left: 0;
         width: 100%;
         text-align: center;
         -webkit-transform: translateY(-50%);
         transform: translateY(-50%);
         -webkit-transition: opacity .2s ease-in-out;
         transition: opacity .2s ease-in-out;
         opacity: 0;
     }

     .page-loading.active>.page-loading-inner {
         opacity: 1;
     }

     .page-loading-inner>span {
         display: block;
         font-size: 1rem;
         font-weight: normal;
         color: #9397ad;
     }

     [data-bs-theme="dark"] .page-loading-inner>span {
         color: #fff;
         opacity: .6;
     }

     .page-spinner {
         display: inline-block;
         width: 2.75rem;
         height: 2.75rem;
         margin-bottom: .75rem;
         vertical-align: text-bottom;
         border: .15em solid #b4b7c9;
         border-right-color: transparent;
         border-radius: 50%;
         -webkit-animation: spinner .75s linear infinite;
         animation: spinner .75s linear infinite;
     }

     [data-bs-theme="dark"] .page-spinner {
         border-color: rgba(255, 255, 255, .4);
         border-right-color: transparent;
     }

     @-webkit-keyframes spinner {
         100% {
             -webkit-transform: rotate(360deg);
             transform: rotate(360deg);
         }
     }

     @keyframes spinner {
         100% {
             -webkit-transform: rotate(360deg);
             transform: rotate(360deg);
         }
     }
 </style>

 <!-- Page loading scripts -->
 <script>
     (function() {
         window.onload = function() {
             const preloader = document.querySelector('.page-loading');
             preloader.classList.remove('active');
             setTimeout(function() {
                 preloader.remove();
             }, 1000);
         };
     })();
 </script>