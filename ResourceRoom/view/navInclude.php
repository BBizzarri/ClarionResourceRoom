 <?php
        if(!loggedIn() || loggedIn()) {
    ?>        
        <nav class="clarion-gold navbar navbar-expand-md sticky-top">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mr-auto">
                        <?php if(userIsAuthorized("shopperHome")) { ?>
                            <li class="nav-item">
                                <a style="color: white; font-size: 20px;" class="nav-link admin-user-nav-bar-text" href="../controller/controller.php?action=shopperHome">Resource Room</a>
                            </li>
                        <?php } ?>

                        <?php if(userIsAuthorized("shopperCart")) { ?>
                            <li class="nav-item">
                                <a style="color: white; font-size: 20px;" class="nav-link admin-user-nav-bar-text" href="../controller/controller.php?action=shopperCart">Cart (<?php if(isset($cart)){ echo($cart->getNumberOfItemsInCart());} else { echo("0");} ?>)</a>
                            </li>
                        <?php } ?>

                        <?php if(userIsAuthorized("shopperOrders")) { ?>
                            <li class="nav-item">
                                <a style="color: white; font-size: 20px;" class="nav-link admin-user-nav-bar-text" href="../controller/controller.php?action=shopperOrders">Orders</a>
                            </li>
                        <?php } ?>

                        <?php if(userIsAuthorized("shopperOrders") && userIsAuthorized("adminInventory")) { ?>
                            <div class="vl"></div>
                        <?php } ?>

                        <?php if(userIsAuthorized("adminOrders")) { ?>
                            <li class="nav-item">
                                <a style="color: white; font-size: 20px;" class="nav-link admin-user-nav-bar-text" href="../controller/controller.php?action=adminOrders">Orders<span class="sr-only">(current)</span></a>
                            </li>
                        <?php } ?>

                        <?php if(userIsAuthorized("adminInventory")) { ?>
                            <li class="nav-item">
                                <a style="color: white; font-size: 20px;" class="nav-link admin-user-nav-bar-text" href="../controller/controller.php?action=adminInventory">Inventory<span class="sr-only"></span></a>
                            </li>
                        <?php } ?>

                        <?php if(userIsAuthorized("adminReports")) { ?>
                            <li class="nav-item">
                                <a style="color: white; font-size: 20px;" class="nav-link admin-user-nav-bar-text" href="../controller/controller.php?action=adminReports">Reports<span class="sr-only"></span></a>
                            </li>
                        <?php } ?>

                        <?php if(userIsAuthorized("adminShoppingList")) { ?>
                            <li class="nav-item">
                                <a style="color: white; font-size: 20px;" class="nav-link admin-user-nav-bar-text" href="../controller/controller.php?action=adminShoppingList">Shopping List<span class="sr-only"></span></a>
                            </li>
                        <?php } ?>

                        <?php if(userIsAuthorized("adminSecurity")) { ?>
                            <li class="nav-item">
                                <a style="color: white; font-size: 20px;" class="nav-link admin-user-nav-bar-text" href="../controller/controller.php?action=adminSecurity">Security<span class="sr-only"></span></a>
                            </li>
                        <?php } ?>
                    </ul>
            <?php if(userIsAuthorized("shopperHome")) { ?>
                <form class="form-inline my-2 my-lg-0">
                    <input class="form-control mr-sm-2" type="text" id="Criteria" placeholder="Search" aria-label="Search">4
                    <input class="btn my-2 my-sm-0" id="searchButton" type="button" value="Search" onclick="generalSearch();"/>
                </form>
            <?php } ?>
            <div class="dropdown account-dropdown">

              <?php if(loggedIn()) { ?>
                  <button class="btn dropdown-toggle" type="button" data-toggle="dropdown"><img src="../Images/person-icon.png" alt="person" height="40px" width="40px"/></button>
                  <ul class="dropdown-menu">
                    <li><a style="color: black;" href="../controller/controller.php?action=accountSettings">Account Settings</a></li>
                    <li>
                        <?php
                                echo "<a class='nav-link color-black' href='../security/index.php?action=SecurityLogOut&RequestedPage=" . urlencode($_SERVER['REQUEST_URI'])  .  "'> Log Out </a>";
                        ?>
                    </li>
                  </ul>
              <?php }
              else {
                echo "<a class='nav-link clarion-white' href='../security/index.php?action=SecurityLogin&RequestedPage=" . urlencode($_SERVER['REQUEST_URI'])  .  "'><i class='fas fa-sign-in-alt'></i> Log In </a>";

              }  ?>


            </div>
            </div>
        </nav>
    <?php
    }
    ?>