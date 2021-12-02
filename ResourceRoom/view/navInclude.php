 <?php
        require_once '../lib/Mobile_Detect.php';
        $detect = new Mobile_Detect;
        if(!loggedIn() || loggedIn()) {
    ?>        
        <nav class="clarion-gold navbar navbar-expand-lg navbar-light sticky-top">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul id="navigation" class="navbar-nav mr-auto">
                        <?php if(userIsAuthorized("shopperHome")) { ?>
                            <li class="nav-item">
                                <a style="color: white; font-size: 20px;" class="nav-link admin-user-nav-bar-text <?php if(strpos($_SERVER['REQUEST_URI'], 'shopperHome')){ echo 'active';}?>" href="../controller/controller.php?action=shopperHome">Resource Room</a>
                            </li>
                        <?php } ?>

                        <?php if(userIsAuthorized("shopperCart")) { ?>
                            <li class="nav-item">
                                <a style="color: white; font-size: 20px;" class="nav-link admin-user-nav-bar-text <?php if(strpos($_SERVER['REQUEST_URI'], 'shopperCart')){ echo 'active';}?>" href="../controller/controller.php?action=shopperCart">Cart (<?php if(isset($_SESSION['itemsInCart'])){ echo($_SESSION['itemsInCart']);} else { echo("0");} ?>)</a>
                            </li>
                        <?php } ?>

                        <?php if(userIsAuthorized("shopperOrders")) { ?>
                            <li class="nav-item">
                                <a style="color: white; font-size: 20px;" class="nav-link admin-user-nav-bar-text <?php if(strpos($_SERVER['REQUEST_URI'], 'shopperOrders') || strpos($_SERVER['REQUEST_URI'], 'shopperSubmitOrder')){ echo 'active';}?>" href="../controller/controller.php?action=shopperOrders">Orders</a>
                            </li>
                        <?php } ?>

                        <?php if(userIsAuthorized("shopperOrders") && userIsAuthorized("adminInventory")) { ?>
                            <div class="vl d-none d-lg-block "></div>
                            <div class="hl"></div>
                        <?php } ?>

                        <?php if(userIsAuthorized("adminOrders")) { ?>
                            <li class="nav-item">
                                <a style="color: white; font-size: 20px;" class="nav-link admin-user-nav-bar-text <?php if(strpos($_SERVER['REQUEST_URI'], 'adminOrders')){ echo 'active';}?>" href="../controller/controller.php?action=adminOrders">Orders<span class="sr-only">(current)</span></a>
                            </li>
                        <?php } ?>

                        <?php if(userIsAuthorized("adminInventory")) { ?>
                            <li class="nav-item">
                                <a style="color: white; font-size: 20px;" class="nav-link admin-user-nav-bar-text <?php if(strpos($_SERVER['REQUEST_URI'], 'adminInventory')){ echo 'active';}?>" href="../controller/controller.php?action=adminInventory&CategoryMode=true">Inventory<span class="sr-only"></span></a>
                            </li>
                        <?php } ?>

                        <?php if(userIsAuthorized("adminReports")) { ?>
                            <li class="nav-item">
                                <a style="color: white; font-size: 20px;" class="nav-link admin-user-nav-bar-text <?php if(strpos($_SERVER['REQUEST_URI'], 'adminReports')){ echo 'active';}?>" href="../controller/controller.php?action=adminReports">Reports<span class="sr-only"></span></a>
                            </li>
                        <?php } ?>

                        <?php if(userIsAuthorized("adminShoppingList")) { ?>
                            <!--<li class="nav-item">
                                <a style="color: white; font-size: 20px;" class="nav-link admin-user-nav-bar-text <?php if(strpos($_SERVER['REQUEST_URI'], 'adminShoppingList')){ echo 'active';}?>" href="../controller/controller.php?action=adminShoppingList">Shopping List<span class="sr-only"></span></a>
                            </li>-->
                        <?php } ?>

                        <?php if(userIsAuthorized("adminSecurity")) { ?>
                            <li class="nav-item">
                                <a style="color: white; font-size: 20px;" class="nav-link admin-user-nav-bar-text <?php if(strpos($_SERVER['REQUEST_URI'], 'adminSecurity')){ echo 'active';}?>" href="../controller/controller.php?action=adminSecurity">Security<span class="sr-only"></span></a>
                            </li>
                        <?php } ?>
                        <?php if($detect->isMobile() or userIsAuthorized("SecurityChangeUserLevel")){ ?>
                            <li class="nav-item">
                                <a style="color: white; font-size: 20px;" class="nav-link admin-user-nav-bar-text <?php if(strpos($_SERVER['REQUEST_URI'], 'mobileAdd')){ echo 'active';}?>" href="../controller/controller.php?action=mobileAdd">Mobile Add<span class="sr-only"></span></a>
                            </li>
                        <?php } ?>
                        <?php if(userIsAuthorized("SecurityChangeUserLevel")) { ?>
                            <li class="nav-item">
                                <a style="color: white; font-size: 20px;" class="nav-link admin-user-nav-bar-text <?php if(strpos($_SERVER['REQUEST_URI'], 'SecurityChangeUserLevel')){ echo 'active';}?>" href="../security/index.php?action=SecurityChangeUserLevel">Change User Level<span class="sr-only"></span></a>
                            </li>
                        <?php } ?>
                    </ul>
            <?php if(userIsAuthorized("shopperHome")) { ?>
                <form id = "navSearchForm" class="form-inline my-2 my-lg-0" action="../controller/controller.php?action=shopperHome&ListType=GeneralSearch" method="post" enctype="multipart/form-data">
                    <input class="form-control" type="text" id="searchCriteria" name="searchCriteria" placeholder="Search" aria-label="Search">
                    <input class="btn my-2 my-sm-0" id="searchButton" type="submit" value="Search"/>
                </form>
            <?php } ?>
            <div class="dropdown account-dropdown">
                <button class="btn dropdown-toggle" type="button" data-toggle="dropdown"><img src="../Images/person-icon.png" alt="person" height="40" width="40"/></button>
                <ul class="dropdown-menu">
                    <li><a class="nav-link color-black" href="../controller/controller.php?action=accountSettings">Settings</a></li>
                </ul>
            </div>
            </div>
        </nav>
    <?php
    }
    ?>