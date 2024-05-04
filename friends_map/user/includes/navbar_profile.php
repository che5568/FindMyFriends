<?php


// Function to check if a given link is active
function isLinkActive($link) {
    return basename($_SERVER['PHP_SELF']) === $link ? 'active' : '';
}
?>
<div class="card overflow-hidden">
      <div class="card-body p-0">
        <img src="../images/bg_blue.jpeg" width="100%" height="100px" alt>
        <div class="row align-items-center">
          <div class="col-lg-4 order-lg-1 order-2">
            <div class="d-flex align-items-center justify-content-around m-4">
            </div>
          </div>
          <div class="col-lg-4 mt-n3 order-lg-2 order-1">
            <div class="mt-n5">
              <div class="d-flex align-items-center justify-content-center mb-2">
                <div class="linear-gradient d-flex align-items-center justify-content-center rounded-circle" style="width: 110px; height: 110px;" ;>
                  <div class="border border-4 border-white d-flex align-items-center justify-content-center rounded-circle overflow-hidden" style="width: 100px; height: 100px;" ;>
                    <img src="https://bootdey.com/img/Content/avatar/avatar1.png" alt class="w-100 h-100">
                  </div>
                </div>
              </div>
              <div class="text-center">
                <h3 class=" mb-3 fw-semibold"><?php echo  $_SESSION["user_name"] ?></h3>
              </div>
            </div>
          </div>
          <div class="col-lg-4 order-last">
          </div>
        </div>
        <ul class="nav nav-pills user-profile-tab justify-content-end mt-2 bg-light-info rounded-2" id="pills-tab" role="tablist">
          <li class="nav-item" role="presentation">
            <button class="nav-link position-relative rounded-0  d-flex align-items-center justify-content-center bg-transparent fs-3 py-6" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="true">
              <i class="fa fa-user me-2 fs-6"></i>
              <a href="user_dashboard.php" class="d-none d-md-block user_nav_links">Dashboard</a>
            </button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link position-relative rounded-0 d-flex align-items-center justify-content-center bg-transparent fs-3 py-6" id="pills-friends-tab" data-bs-toggle="pill" data-bs-target="#pills-friends" type="button" role="tab" aria-controls="pills-friends" aria-selected="false" tabindex="-1">
              <i class="fa fa-users me-2 fs-6"></i>
              <a href="friends.php" class="d-none d-md-block user_nav_links">Friends</a>
            </button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link position-relative  rounded-0 d-flex align-items-center justify-content-center bg-transparent fs-3 py-6" id="pills-followers-tab" data-bs-toggle="pill" data-bs-target="#pills-followers" type="button" role="tab" aria-controls="pills-followers" aria-selected="false" tabindex="-1">
              <i class="fa fa-heart me-2 fs-6"></i>
              <a href="friend_request_list.php" class="d-none d-md-block user_nav_links">Friend Request</a>
            </button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link position-relative  rounded-0 d-flex align-items-center justify-content-center bg-transparent fs-3 py-6" id="pills-gallery-tab" data-bs-toggle="pill" data-bs-target="#pills-gallery" type="button" role="tab" aria-controls="pills-gallery" aria-selected="false" tabindex="-1">
              <i class="fa fa-user  me-2 fs-6"></i>
              <a href="find_friend.php" class="d-none d-md-block user_nav_links">Add Friends</a>
            </button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link position-relative rounded-0 d-flex align-items-center justify-content-center bg-transparent fs-3 py-6" id="pills-gallery-tab" data-bs-toggle="pill" data-bs-target="#pills-gallery" type="button" role="tab" aria-controls="pills-gallery" aria-selected="false" tabindex="-1">
              <i class="fa fa-sign-out fa-2x  me-2 fs-6"></i>
              <a href="../logout.php" class="d-none d-md-block user_nav_links">Logout</a>
            </button>
          </li>
        </ul>
      </div>
    </div>