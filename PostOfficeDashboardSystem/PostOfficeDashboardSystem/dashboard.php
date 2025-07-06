<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Post Office Automation System</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
  <style>
    body {
      background: url('https://upload.wikimedia.org/wikipedia/commons/6/63/India_Post_Office.jpg') no-repeat center center fixed;
      background-size: cover;
      backdrop-filter: brightness(0.7);
      color: #fff;
    }
    .module-card {
      background-color: rgba(255, 255, 255, 0.85);
      border-radius: 1rem;
      transition: 0.3s ease;
    }
    .module-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
    }
    .icon-circle {
      width: 60px;
      height: 60px;
      background-color: rgb(237, 30, 7);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 24px;
      margin-bottom: 10px;
      color: white;
    }
    .navbar {
      background-color: rgba(222, 6, 6, 0.95) !important;
    }
    .navbar-brand {
      color: #fff !important;
      font-size: 1.25rem;
    }
    .navbar-nav .nav-link {
      color: #fff !important;
    }
    .dropdown-menu {
      background-color: #fff;
    }
    .container {
      padding-top: 80px;
    }
    h2 {
      color: white;
      font-size: 2.5rem;
      margin-bottom: 30px;
    }
  </style>
</head>
<body>

<!-- ✅ Navbar Start -->
<nav class="navbar navbar-expand-lg navbar-dark shadow-sm">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold" href="#">📮 Post Office Dashboard</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarContent">
      <ul class="navbar-nav ms-auto align-items-center">
        <li class="nav-item me-3">
          <a class="nav-link" href="modules/DailyPostReport.php"><i class="fas fa-file-alt me-1"></i>Reports</a>
        </li>
        <li class="nav-item me-3">
          <a class="nav-link" href="modules/TrackPost.php"><i class="fas fa-map-marker-alt me-1"></i>Track</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
            <img src="https://i.pravatar.cc/40?img=12" alt="Admin" class="rounded-circle me-2" width="40" height="40" />
            <span>Admin</span>
          </a>
          <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="#">Profile</a></li>
            <li><a class="dropdown-item" href="#">Settings</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item text-danger" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>
<!-- ✅ Navbar End -->

<div class="container py-5">
  <h2 class="text-center mb-4">📮 Post Office Automation Dashboard</h2>
  <div class="row g-4">

    <div class="col-md-4">
      <a href="modules/register_post.php" class="text-decoration-none text-dark">
        <div class="card p-4 text-center module-card">
          <div class="icon-circle mx-auto text-primary"><i class="fas fa-envelope"></i></div>
          <h5>Registered Post</h5>
          <p>Register and track traditional postal items securely.</p>
        </div>
      </a>
    </div>

    <div class="col-md-4">
      <a href="modules/SpeedPost.php" class="text-decoration-none text-dark">
        <div class="card p-4 text-center module-card">
          <div class="icon-circle mx-auto text-danger"><i class="fas fa-bolt"></i></div>
          <h5>Speed Post</h5>
          <p>Quick delivery with real-time status updates.</p>
        </div>
      </a>
    </div>

    <div class="col-md-4">
      <a href="modules/ParcelPost.php" class="text-decoration-none text-dark">
        <div class="card p-4 text-center module-card">
          <div class="icon-circle mx-auto text-success"><i class="fas fa-box"></i></div>
          <h5>Parcel Post</h5>
          <p>Manage, weigh, and track parcel deliveries efficiently.</p>
        </div>
      </a>
    </div>

    <div class="col-md-4">
      <a href="modules/MoneyOrder.php" class="text-decoration-none text-dark">
        <div class="card p-4 text-center module-card">
          <div class="icon-circle mx-auto text-warning"><i class="fas fa-money-bill-wave"></i></div>
          <h5>Money Order</h5>
          <p>Send and confirm money orders digitally.</p>
        </div>
      </a>
    </div>

    <div class="col-md-4">
      <a href="modules/ECard.php" class="text-decoration-none text-dark">
        <div class="card p-4 text-center module-card">
          <div class="icon-circle mx-auto text-info"><i class="fas fa-gift"></i></div>
          <h5>E-Card</h5>
          <p>Send digital greeting cards with style and ease.</p>
        </div>
      </a>
    </div>

    <div class="col-md-4">
      <a href="modules/InquiryModule.php" class="text-decoration-none text-dark">
        <div class="card p-4 text-center module-card">
          <div class="icon-circle mx-auto text-secondary"><i class="fas fa-question-circle"></i></div>
          <h5>Inquiry</h5>
          <p>Track post status and manage customer questions.</p>
        </div>
      </a>
    </div>

    <div class="col-md-4">
      <a href="modules/DailyPostReport.php" class="text-decoration-none text-dark">
        <div class="card p-4 text-center module-card">
          <div class="icon-circle mx-auto text-dark"><i class="fas fa-file-alt"></i></div>
          <h5>Daily Reports</h5>
          <p>Generate and review daily post transaction reports.</p>
        </div>
      </a>
    </div>

    <div class="col-md-4">
      <a href="modules/TrackPost.php" class="text-decoration-none text-dark">
        <div class="card p-4 text-center module-card">
          <div class="icon-circle mx-auto text-success"><i class="fas fa-map-marker-alt"></i></div>
          <h5>Tracking</h5>
          <p>Real-time post tracking with status updates.</p>
        </div>
      </a>
    </div>

    <div class="col-md-4">
      <a href="modules/LoginModule.php" class="text-decoration-none text-dark">
        <div class="card p-4 text-center module-card">
          <div class="icon-circle mx-auto text-primary"><i class="fas fa-user-shield"></i></div>
          <h5>Admin Login</h5>
          <p>Secure login and access control for administrators.</p>
        </div>
      </a>
    </div>

  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
