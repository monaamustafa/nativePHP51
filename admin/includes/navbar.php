<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Navbar</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
      aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="dashboard.php">Home</a> 
        </li>
        <li class="nav-item">
          <a class="nav-link" href="members.php">members</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="products.php">products</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" id="navbarDropdown" role="button" data-bs-toggle="dropdown"
            aria-expanded="false">
            <?= $_SESSION['FULLNAME']; ?>
          </a>
          <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
            <li><a class="dropdown-item" href="members.php?role=admin">admins</a></li>
            <?php if($_SESSION['LANG']=='ar'):?>
            <li><a class="dropdown-item" href="?lang=en">English</a></li>
            <?php else:?>
            <li><a class="dropdown-item" href="?lang=ar">اللغة العربيه</a></li>
            <?php endif?>
            <li>
              <hr class="dropdown-divider">
            </li>
            <li><a class="dropdown-item" href="logout.php">logout</a></li>
          </ul>
        </li>
    </div>
  </div>
</nav>