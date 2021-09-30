<?php
    $title = " Shopper Home Page";
    require '../view/headerInclude.php';
?>
<html>
<body>
    <section id="main" class="clarion-blue">
        <div class="container-fluid">
            <div class ="row">
                <div class="col-2 sidebar">
                    <div class="sidebar-elements">
                        <h3 class="sidebar-heading">Categories</h3>
                        <ul class="nav flex-column">
                            <?php foreach ($CategoryResults as $CategoryRow) {
                                ?>
                                <li class="nav-item">
                                    <a class="category nav-link " href="../controller/controller.php?action=shopperHome&CATEGORYID=<?php echo $CategoryRow['CATEGORYID']?>&DESCRIPTION=<?php echo $CategoryRow['DESCRIPTION']?>&Display=<?php echo 'category' ?>"><?php echo htmlspecialchars($CategoryRow['DESCRIPTION']) ?></a>
                                </li>
                                <?php
                            }
                            ?>
                        </ul>
                    </div>
                </div>
                <div class="col-10">
                    <div class="container-fluid">
                        <h3 class="category-heading"><?php echo htmlspecialchars($CategoryHeader)?></h3>
                        <div class="row">
                            <?php foreach ($ProductResults as $ProductRow) {
                                ?>
                            <div class="col-sm-6 col-md-4 col-lg-4 col-xl-2 py-3">
                                <div class="card h-100">
                                    <div class="card-header">
                                        <img src="https://dummyimage.com/256x256/000/fff.jpg" class="card-img-top" alt="...">
                                    </div>
                                    <div class="card-body">
                                        <h4 class="card-title"><?php echo htmlspecialchars($ProductRow['NAME']) ?></h4>
                                    </div>
                                    <div class="card-footer d-flex flex-column">
                                        <form id="card-form" action="../controller/controller.php?action=processAddToCart&ProductID=<?php echo $ProductRow['PRODUCTID']?>" method="post" enctype="multipart/form-data">
                                            <div class="form-group row flex-column" id="shopperAddToCartButton">
                                                <input type="number" id="QTYRequested" name="QTYRequested" min="1" max="<?php echo htmlspecialchars($ProductRow['QTYONHAND'])?>">
                                                <input type="submit" class="btn btn-primary" value="Add To Cart">
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>
</html>
<?php
    require '../view/footerInclude.php';
?>