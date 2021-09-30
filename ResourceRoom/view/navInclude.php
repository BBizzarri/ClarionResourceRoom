 <?php 
        if(!loggedIn() || loggedIn()) {
    ?>        
        <nav class="clarion-gold navbar navbar-expand-md sticky-top">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item">
                            <a style="color: white; font-size: 20px;" class="nav-link admin-user-nav-bar-text" href="../controller/controller.php?action=shopperHome">Resource Room</a>
                        </li>
                        <li class="nav-item">
                            <a style="color: white; font-size: 20px;" class="nav-link admin-user-nav-bar-text" href="../controller/controller.php?action=shopperCart">Cart (0)</a>
                        </li>
                        <li class="nav-item">
                            <a style="color: white; font-size: 20px;" class="nav-link admin-user-nav-bar-text" href="../controller/controller.php?action=shopperOrders">Orders</a>
                        </li>
                        <div class="vl"></div>
                        <li class="nav-item">
                            <a style="color: white; font-size: 20px;" class="nav-link admin-user-nav-bar-text" href="../controller/controller.php?action=adminOrders">Orders (0)<span class="sr-only">(current)</span></a>
                            </li>
                            <li class="nav-item">
                                <a style="color: white; font-size: 20px;" class="nav-link admin-user-nav-bar-text" href="../controller/controller.php?action=adminInventory">Inventory<span class="sr-only"></span></a>
                            </li>
                            <li class="nav-item">
                                <a style="color: white; font-size: 20px;" class="nav-link admin-user-nav-bar-text" href="../controller/controller.php?action=adminReports">Reports<span class="sr-only"></span></a>
                            </li>
                            <li class="nav-item">
                                <a style="color: white; font-size: 20px;" class="nav-link admin-user-nav-bar-text" href="../controller/controller.php?action=adminShoppingList">Shopping List<span class="sr-only"></span></a>
                            </li>
                            <li class="nav-item">
                                <a style="color: white; font-size: 20px;" class="nav-link admin-user-nav-bar-text" href="../controller/controller.php?action=adminSecurity">Security<span class="sr-only"></span></a>
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
                    <button class="btn my-2 my-sm-0" type="submit">Search</button>
                </form>
            <div class="dropdown account-dropdown">
                <button class="btn dropdown-toggle" type="button" data-toggle="dropdown"><img src="../Images/person-icon.png" alt="person" height="40px" width="40px"/></button>
              <ul class="dropdown-menu">
                <li><a style="color: black;" href="#">Account Settings</a></li>
                <li><a style="color: black" href="#">Logout</a></li>
              </ul>
            </div>
            </div>
        </nav>
    <?php
    }
    ?>