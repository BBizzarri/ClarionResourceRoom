
    <nav class="navbar navbar-expand-md clarion-gold navbar-light sticky-top">
        <a class="navbar-brand d-md-none d-lg-block" href="../controller/controller.php?action=Home">Resource Room</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="../controller/controller.php?action=Home">Home <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../security/index.php">Security <span class="sr-only"></span></a>
                </li>
                <li class="nav-item">
                    <?php
                        if (!loggedIn()) {
                            echo "<a class='nav-link' href='../security/index.php?action=SecurityLogin&RequestedPage=" . urlencode($_SERVER['REQUEST_URI'])  .  "'><i class='fas fa-sign-in-alt'></i> Log In </a>";
                        }else {
                            echo "<a class='nav-link' href='../security/index.php?action=SecurityLogOut&RequestedPage=" . urlencode($_SERVER['REQUEST_URI'])  .  "'>Log Out " . $_SESSION['UserName'] . " </a>";
                        }
                    ?>

                </li>
            </ul>
            <form class="form-inline my-2 my-lg-0">
                <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
            </form>
        </div>
    </nav>

