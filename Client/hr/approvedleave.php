<?php
session_start();

// Check if user is not logged in
if (!isset($_SESSION['hr_id'])) {
    // Redirect to login page
    header("Location: ../index.php#loginsection");
    exit();
}

require_once '../../server/config/database.php';
$stmt1 = $conn->prepare("
        SELECT COUNT(*) 
        FROM day_off_requests 
        WHERE decision = 'pending' 
    ");
$stmt1->execute();
$stmt1->bind_result($drcount);
$stmt1->fetch();
$stmt1->close();

$stmt2 = $conn->prepare("
        SELECT COUNT(*) 
        FROM leave_requests 
        WHERE decision = 'pending' 
    ");
$stmt2->execute();
$stmt2->bind_result($lrcount);
$stmt2->fetch();
$stmt2->close();

$pending_requests = $drcount + $lrcount;

?>
<!doctype html>
<html lang="en" data-bs-theme="auto">

<head>
    <script src="../css/assets/js/color-modes.js"></script>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.122.0">
    <title>UTAB</title>
    <link rel="icon" type="image/png" href="../img/utab_logo.png">


    <link rel="canonical" href="https://getbootstrap.com/docs/5.3/examples/footers/">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@docsearch/css@3">

    <link href="../css/assets/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="../stylesheet" href="css/carousel1.css">
    <link rel="../stylesheet" href="css/checkout.css">
    

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
        .hnav-link{
            color:#007A12;
            font-weight: bold;
            font-family: 'Poppins', Helvetica, Arial, Lucida, sans-serif;
            text-decoration: none;
            
        }
        .p-color{
            background-color: #007A12;
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


    <header data-bs-theme="">
    <div class="text-center bg-light fixed-top row text-muted shadow"  style="transition:transform 3s ease-in;border-bottom:4px solid #007a12">
      <div class="col-8">
      <nav class="navbar navbar-expand-md">
      <div class="container-fluid">
        <a class="navbar-brand" href="account.php"><img src="../img/logo.png"width="100px" height="30px"></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse"
          aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse col-12 pt-3 justify-content-left" id="navbarCollapse">
        <ul class="navbar-nav me-auto my-2 my-lg-0 navbar-nav-scroll">              
            <li class="nav-item">
              <div class="dropdown ms-5">
                <a href="#" class="hnav-link d-block text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                   REQUESTS<div class="badge ms-2 p-1 bg-warning text-success"><b><?php echo $pending_requests; ?></b></div>
                </a>
                <ul class="dropdown-menu text-small">
                    <li><a class="hnav-link ms-4" href="dayoffrequests.php">DAY OFF<div class="badge ms-1 p-1 bg-warning text-success"><b><?php echo $drcount; ?></b></div></a></li>
                    <li><a class="hnav-link ms-4" href="leaverequests.php">LEAVE<div class="badge ms-1 p-1 bg-warning text-success"><b><?php echo $lrcount; ?></b></div></a></li>
                </ul>
            </div>
            </li>
            <li class="nav-item">
              <div class="dropdown ms-5">
                <a href="#" class="hnav-link d-block text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                   APPROVED
                </a>
                <ul class="dropdown-menu text-small">
                    <li><a class="hnav-link ms-5" href="approveddayoff.php">DAY OFF</a></li>
                    <li><a class="hnav-link ms-5" href="approvedleave.php">LEAVE</a></li>
                </ul>
            </div>
            </li>
            <li class="nav-item">
              <div class="dropdown ms-5">
                <a href="#" class="hnav-link d-block text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                   REJECTED
                </a>
                <ul class="dropdown-menu text-small">
                    <li><a class="hnav-link ms-5" href="rejecteddayoff.php">DAY OFF</a></li>
                    <li><a class="hnav-link ms-5" href="rejectedleave.php">LEAVE</a></li>
                </ul>
            </div>
            </li>
            <li class="nav-item">
              <a class="hnav-link py-4 ms-5" href="staffs.php">STAFFS
              <div class="badge bg-light text-info"><?php //echo $row4; ?></div>
              </a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
      </div>
      <div class="col-1 py-4"><b>HR</b></div>
    <div class="col-3"><a class="btn py-4 hnav-link text-danger" href="../../Server/hr_logout.php"><b>LOG OUT</b></a>
    </div>
  </header>

    <br><br><br><br>
    <main class="text-muted">
        <hr>
        <!-- Marketing messaging and featurettes
  ================================================== -->
        <!-- Wrap the rest of the page in another container to center all the content. -->

        <div class="container">
            <!-- MAIN US ROW -->
             <div class="row">
             <div class="col-12" style="overflow-x: auto; width:1200px;">
            <div class="" style="width:1110px;">
                <div class="alert p-color text-light h6 row">
                    <div class="col-2">Name</div>
                    <div class="col-4">Request Reason</div>
                    <div class="col-3">Period</div>
                    <div class="col-1">Days</div>
                    <div class="col-2">Requested on</div>
                </div>
                <?php

                require_once '../../server/config/database.php';

                $sql = "SELECT * FROM leave_requests INNER JOIN `staff` ON leave_requests.staff_id = staff.staff_id WHERE decision='Approved'";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {


                ?>
                        <div class="alert alert-dark text-muted row">
                            <div class="col-2"><?php echo $row['first_name']." ".$row['other_name'] ?></div>
                            <div class="col-4"><?php echo $row['leave_reason'] ?></div>
                            <div class="col-3"><?php echo "From <b>".$row['start_date']."</b> To <b>".$row['end_date']. "</b>" ?></div>
                            <div class="col-1"><?php echo $row['days_requested'] ?></div>
                            <div class="col-1" style="white-space: nowrap;"><?php echo $row['created_at'] ?></div>
                        </div>

                <?php
                    }
                } else {
                    echo "<p>No staff found.</p>";
                }

                $conn->close();
                ?>

            </div>
            <!-- /.row  -->
             </div>
             </div>
        </div><!-- /.container -->

    </main>
    <script src="../css/assets/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>