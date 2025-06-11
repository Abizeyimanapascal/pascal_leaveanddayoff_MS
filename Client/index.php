<?php session_start(); ?>
<?php
require_once '../server/config/database.php';
$stafflogin_result = "";
if (isset($_POST['staffloginbtn'])) {
    $username = trim($_POST['staffusername']);
    $password = trim($_POST['staffpassword']);

    $stmt = $conn->prepare("SELECT `staff_id`, `password` FROM `staff` WHERE `username` = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($staff_id, $stored_password);
        $stmt->fetch();

        if (password_verify($password, $stored_password)) {
            // Login successful
            session_regenerate_id(true);
            $_SESSION['staff_id'] = $staff_id;
            $_SESSION['username'] = $username;
            $stafflogin_result = "success";
        } else {
            $stafflogin_result = "invalidpwd";
        }
    } else {
        $stafflogin_result = "notfound";
    }

    $stmt->close();
}
?>
<?php
require_once '../server/config/database.php';
$hrlogin_result = "";
if (isset($_POST['hrloginbtn'])) {
    $hr_password = trim($_POST['hrpassword']);

    $stmt = $conn->prepare("SELECT `hr_id`, `hr_password` FROM `hr` WHERE `hr_password` = ?");
    $stmt->bind_param("s", $hr_password);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($hr_id, $hr_stored_password);
        $stmt->fetch();
        // Login successful
        session_regenerate_id(true);
        $_SESSION['hr_id'] = $hr_id;
        $hrlogin_result = "success";
    } else {
        $hrlogin_result = "notfound";
    }

    $stmt->close();
}
?>

<?php if (!empty($stafflogin_result)) { ?>
    <script>
        window.onload = function() {
            window.location.hash = 'loginsection';
        };
    </script>
<?php } ?>

<!doctype html>
<html lang="en" data-bs-theme="auto">

<head>
    <script src="../assets/js/color-modes.js"></script>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.122.0">
    <title>UTAB</title>
    <link rel="icon" type="image/png" href="img/utab_logo.png">


    <link rel="canonical" href="https://getbootstrap.com/docs/5.3/examples/footers/">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@docsearch/css@3">

    <link href="css/assets/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/carousel1.css">
    <link rel="stylesheet" href="css/checkout.css">

    <!-- Include SweetAlert2 from CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <style>
        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            user-select: none;
        }

        @media (min-width: 768px) {
            .bd-placeholder-img-lg {
                font-size: 3.5rem;
            }
        }

        .b-example-divider {
            width: 100%;
            height: 3rem;
            background-color: rgba(0, 0, 0, .1);
            border: solid rgba(0, 0, 0, .15);
            border-width: 1px 0;
            box-shadow: inset 0 .5em 1.5em rgba(0, 0, 0, .1), inset 0 .125em .5em rgba(0, 0, 0, .15);
        }

        .b-example-vr {
            flex-shrink: 0;
            width: 1.5rem;
            height: 100vh;
        }

        .bi {
            vertical-align: -.125em;
            fill: currentColor;
        }

        .nav-scroller {
            position: relative;
            z-index: 2;
            height: 2.75rem;
            overflow-y: hidden;
        }

        .nav-scroller .nav {
            display: flex;
            flex-wrap: nowrap;
            padding-bottom: 1rem;
            margin-top: -1px;
            overflow-x: auto;
            text-align: center;
            white-space: nowrap;
            -webkit-overflow-scrolling: touch;
        }

        .btn-bd-primary {
            --bd-violet-bg: #712cf9;
            --bd-violet-rgb: 112.520718, 44.062154, 249.437846;

            --bs-btn-font-weight: 600;
            --bs-btn-color: var(--bs-white);
            --bs-btn-bg: var(--bd-violet-bg);
            --bs-btn-border-color: var(--bd-violet-bg);
            --bs-btn-hover-color: var(--bs-white);
            --bs-btn-hover-bg: #6528e0;
            --bs-btn-hover-border-color: #6528e0;
            --bs-btn-focus-shadow-rgb: var(--bd-violet-rgb);
            --bs-btn-active-color: var(--bs-btn-hover-color);
            --bs-btn-active-bg: #5a23c8;
            --bs-btn-active-border-color: #5a23c8;
        }

        .bd-mode-toggle {
            z-index: 1500;
        }

        .bd-mode-toggle .dropdown-menu .active .bi {
            display: block !important;
        }

        .nav-link {
            color: gray;
        }

        .p-color {
            background-color: #007A12;
            color: white;
        }

        .s-color {
            background-color: #2ea3f2;
            color: white;
        }
    </style>


    <!-- Custom styles for this template -->

</head>

<body>
    <svg xmlns="http://www.w3.org/2000/svg" class="d-none">
        <symbol id="check2" viewBox="0 0 16 16">
            <path
                d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z" />
        </symbol>
        <symbol id="circle-half" viewBox="0 0 16 16">
            <path d="M8 15A7 7 0 1 0 8 1v14zm0 1A8 8 0 1 1 8 0a8 8 0 0 1 0 16z" />
        </symbol>
        <symbol id="moon-stars-fill" viewBox="0 0 16 16">
            <path
                d="M6 .278a.768.768 0 0 1 .08.858 7.208 7.208 0 0 0-.878 3.46c0 4.021 3.278 7.277 7.318 7.277.527 0 1.04-.055 1.533-.16a.787.787 0 0 1 .81.316.733.733 0 0 1-.031.893A8.349 8.349 0 0 1 8.344 16C3.734 16 0 12.286 0 7.71 0 4.266 2.114 1.312 5.124.06A.752.752 0 0 1 6 .278z" />
            <path
                d="M10.794 3.148a.217.217 0 0 1 .412 0l.387 1.162c.173.518.579.924 1.097 1.097l1.162.387a.217.217 0 0 1 0 .412l-1.162.387a1.734 1.734 0 0 0-1.097 1.097l-.387 1.162a.217.217 0 0 1-.412 0l-.387-1.162A1.734 1.734 0 0 0 9.31 6.593l-1.162-.387a.217.217 0 0 1 0-.412l1.162-.387a1.734 1.734 0 0 0 1.097-1.097l.387-1.162zM13.863.099a.145.145 0 0 1 .274 0l.258.774c.115.346.386.617.732.732l.774.258a.145.145 0 0 1 0 .274l-.774.258a1.156 1.156 0 0 0-.732.732l-.258.774a.145.145 0 0 1-.274 0l-.258-.774a1.156 1.156 0 0 0-.732-.732l-.774-.258a.145.145 0 0 1 0-.274l.774-.258c.346-.115.617-.386.732-.732L13.863.1z" />
        </symbol>
        <symbol id="sun-fill" viewBox="0 0 16 16">
            <path
                d="M8 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM8 0a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 0zm0 13a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 13zm8-5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2a.5.5 0 0 1 .5.5zM3 8a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2A.5.5 0 0 1 3 8zm10.657-5.657a.5.5 0 0 1 0 .707l-1.414 1.415a.5.5 0 1 1-.707-.708l1.414-1.414a.5.5 0 0 1 .707 0zm-9.193 9.193a.5.5 0 0 1 0 .707L3.05 13.657a.5.5 0 0 1-.707-.707l1.414-1.414a.5.5 0 0 1 .707 0zm9.193 2.121a.5.5 0 0 1-.707 0l-1.414-1.414a.5.5 0 0 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .707zM4.464 4.465a.5.5 0 0 1-.707 0L2.343 3.05a.5.5 0 1 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .708z" />
        </symbol>
    </svg>

    <div class="dropdown position-fixed bottom-0 end-0 mb-3 me-3 bd-mode-toggle">
        <button class="btn btn-bd-primary py-2 dropdown-toggle d-flex align-items-center" id="bd-theme" type="button"
            aria-expanded="false" data-bs-toggle="dropdown" aria-label="Toggle theme (auto)">
            <svg class="bi my-1 theme-icon-active" width="1em" height="1em">
                <use href="#circle-half"></use>
            </svg>
            <span class="visually-hidden" id="bd-theme-text">Toggle theme</span>
        </button>
        <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="bd-theme-text">
            <li>
                <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="light"
                    aria-pressed="false">
                    <svg class="bi me-2 opacity-50" width="1em" height="1em">
                        <use href="#sun-fill"></use>
                    </svg>
                    Light
                    <svg class="bi ms-auto d-none" width="1em" height="1em">
                        <use href="#check2"></use>
                    </svg>
                </button>
            </li>
            <li>
                <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="dark"
                    aria-pressed="false">
                    <svg class="bi me-2 opacity-50" width="1em" height="1em">
                        <use href="#moon-stars-fill"></use>
                    </svg>
                    Dark
                    <svg class="bi ms-auto d-none" width="1em" height="1em">
                        <use href="#check2"></use>
                    </svg>
                </button>
            </li>
            <li>
                <button type="button" class="dropdown-item d-flex align-items-center active" data-bs-theme-value="auto"
                    aria-pressed="true">
                    <svg class="bi me-2 opacity-50" width="1em" height="1em">
                        <use href="#circle-half"></use>
                    </svg>
                    Auto
                    <svg class="bi ms-auto d-none" width="1em" height="1em">
                        <use href="#check2"></use>
                    </svg>
                </button>
            </li>
        </ul>
    </div>


    <header data-bs-theme="bg-light">
        <div class="text-center sticky-top row bg-light shadow" style="transition:transform 3s ease-in;font-family: 'Poppins', Helvetica, Arial, Lucida, sans-serif;border-bottom:4px solid #2ea3f2">
            <div class="col-2 h3">
                <a class="nav-link text-primary mt-2" href="index.php"><img src="img/logo.png" width="120px" height="30px"></a>
            </div>

            <div class="col-8">
                <nav class="navbar navbar-expand-md mt-2">
                    <b>LEAVE AND DAY OFF MANAGEMENT SYSTEM</b>
                </nav>
            </div>

            <div class="col-2"><a class="m-2 btn btn-primary s-color text-light" href="#loginsection"><b>Sing in</b></a>
            </div>
        </div>

        <div id="myCarousel" class="carousel slide mb-6" data-bs-ride="carousel">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#myCarousel" data-bs-slide-to="0" class="active"
                    aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#myCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#myCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
            </div>
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="img/utab1.jpg" alt="" style="width: 100%;height: 100%;">
                    <div class="container">
                        <div class="carousel-caption center-start">
                            <h1 class="pic1an1">LEAVE AND DAY OFF MS</h1>
                            <p class="top-5 pic1an2 text-light h1">Request for leave or a day off</p>

                        </div>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="img/utab2.jpeg" alt="" style="width: 100%;height: 100%;">
                    <div class="container">
                        <div class="carousel-caption">
                            <h1 class="pic2an1 top-5">Track your leave and day off status</h1>
                            <p class=" top-5">Track your leave and day off status</p>
                            <p><img src="img/communication.png" alt="" width="150"></p>
                        </div>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="img/utab3.jpg" alt="" style="width: 100%;height: 100%;">
                    <div class="container">
                        <div class="carousel-caption text-center">
                            <h1 class="pic3an1">Guided Requesting Process</h1>
                            <p class="pic3an2">Staff make direct contact with HR</p>
                            <p><img src="img/utab3.png" alt="" width="150"></p>
                        </div>
                    </div>
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#myCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#myCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>

    </header>

    <main class="text-muted">

        <!-- Marketing messaging and featurettes
  ================================================== -->
        <!-- Wrap the rest of the page in another container to center all the content. -->

        <div class="container marketing">
            <a name="loginsection"></a>
            <!-- SEARCH  ROW -->

            <!-- /.row  -->

            <nav>
                <div class="nav nav-tabs mb-3 justify-content-center text-muted" id="nav-tab" role="tablist" style="font-weight: bold">
                    <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-staff"
                        type="button" role="tab" aria-controls="nav-staff" aria-selected="true">STAFF LOGIN</button>
                    <button class="nav-link" id="nav-users-tab" data-bs-toggle="tab" data-bs-target="#nav-hr"
                        type="button" role="tab" aria-controls="nav-hr" aria-selected="false">HR LOGIN</button>
                </div>
            </nav>

            <div class="tab-content row" id="nav-tabContent">
                <div class="col-md-3 text-muted">
                    Welcome to <b>LEAVE AND DAY OFF MS</b>! We’re glad to have you here. 
                    This platform is designed to help you manage your work tasks more efficiently. 
                    From requesting leave days and day offs to accessing important announcements and resources.
                </div>
                <div class="col-md-1"></div>
                <div class="tab-pane fade show active col-md-6" id="nav-staff" role="tabpanel" aria-labelledby="nav-home-tab">
                    <form class="needs-validation" method="POST" novalidate>
                        <div class="form-floating">
                            <input type="text" name="staffusername" class="form-control" id="floatingUsername"
                                placeholder="Username" required />
                            <label for="floatingUsername" class="text-muted"><img src="feather/user.svg"> Username</label>
                            <div class="invalid-feedback">
                                Please Enter your staff username.
                            </div>
                        </div><br>

                        <div class="form-floating">
                            <input type="password" name="staffpassword" class="form-control" id="floatingPassword"
                                placeholder="Password" required />
                            <label for="floatingPassword" class="text-muted"><img src="feather/lock.svg"> Password</label>
                            <div class="invalid-feedback">
                                Please Enter your staff password.
                            </div>
                        </div><br>
                        <input type="submit" name="staffloginbtn" value="Login" class="btn btn-primary s-color w-100 py-2">
                    </form><br>
                    <script>
                        <?php if ($stafflogin_result === "success") { ?>
                            Swal.fire({
                                icon: 'success',
                                title: 'Done!',
                                text: 'Login successful.'
                            }).then(() => {
                                window.location.href = 'account.php';
                            });
                        <?php } else if ($stafflogin_result === "invalidpwd") { ?>
                            Swal.fire({
                                icon: 'error',
                                title: 'Invalid!',
                                text: 'Invalid password.'
                            }).then(() => {
                                window.location.href = 'index.php';
                            });
                        <?php } else if ($stafflogin_result === "notfound") { ?>
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops!!',
                                text: 'Not found: Try again, or Contact your HR...'
                            }).then(() => {
                                window.location.href = 'index.php';
                            });
                        <?php } ?>
                    </script>
                </div>

                <div class="tab-pane fade col-md-6" id="nav-hr" role="tabpanel" aria-labelledby="nav-users-tab">
                    <form class="needs-validation" method="POST" action="index.php" novalidate>
                        <div class="form-floating">
                            <input type="password" name="hrpassword" class="form-control" id="floatingPassword"
                                placeholder="Password" required />
                            <label for="floatingPassword" class="text-muted"><img src="feather/lock.svg"> Password</label>
                            <div class="invalid-feedback">
                                Please Enter the password.
                            </div>
                        </div><br>
                        <input type="submit" name="hrloginbtn" value="Login" class="btn p-color btn-success w-100 py-2">
                    </form><br>
                    <script>
                        <?php if ($hrlogin_result === "success") { ?>
                            Swal.fire({
                                icon: 'success',
                                title: 'Done!',
                                text: 'Login successful.'
                            }).then(() => {
                                window.location.href = 'hr/account.php';
                            });
                        <?php } else if ($hrlogin_result === "notfound") { ?>
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops!!',
                                text: 'Not found: Try again, or Contact your HR...'
                            }).then(() => {
                                window.location.href = 'index.php';
                            });
                        <?php } ?>
                    </script>
                </div>
            </div>


        </div><!-- /.container -->
    </main>


    <!-- FOOTER -->
    <br><br>
    <hr>
    <footer class="p-5  bg-dark text-light">
        <br><br>
        <div class="row">
            <div class="col-sm-4 mb-3">
                <span class="text-warning h4">...</span><br><br>
                <a href="ttps://utab.ac.rw/" style="text-decoration: none;">UTAB Website</a>
            </div>

            <div class="col-sm-2 mb-3">
                <ul class="nav flex-column">
                    <li class="nav-item mb-2"><a href="index.php?search" class="nav-link p-0">Home</a></li>
                    <li class="nav-item mb-2"><a href="aboutus.php" class="nav-link p-0">About-us</a></li>
                    <li class="nav-item mb-2"><a href="services.php" class="nav-link p-0">Services</a></li>
                </ul>
            </div>


            <div class="col-sm-2 mb-3">
                <ul class="nav flex-column">
                    <li class="nav-item mb-2"><a href="index.php?search" class="nav-link p-0">Home</a></li>
                    <li class="nav-item mb-2"><a href="aboutus.php" class="nav-link p-0">About-us</a></li>
                    <li class="nav-item mb-2"><a href="services.php" class="nav-link p-0">Services</a></li>
                </ul>
            </div>

            <div class="col-md-2 ">
                <img src="img/logo.png" height="80px" width="" alt="" style="margin-left:15%;"> <br>
            </div>
        </div>

        <div class="">
            <p class="text-center">&copy; 2025 UTAB. All right reserved.</p>
        </div>
        <br><br>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>

    <script src="{{ asset('assets/dist/js/bootstrap.bundle.min.js') }}"></script>



    <script src="css/assets/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/checkout.js"></script>
</body>

</html>