<?php
    $title = " Shopper Home Page";
    require '../view/headerInclude.php';
?>
<html>
<body>
    <section id="main" class="clarion-blue">
        <div class="container-fluid">
            <div class ="row">
                <div class="col-lg-2 hidden-xs sidebar">
                    <div class="sidebar-elements w-100">
                        <h3 class="sidebar-heading">Categories</h3>
                        <ul class="nav flex-column">
                            <?php foreach ($CategoryArray as $category) {
                                ?>
                                <li class="nav-item">
                                    <a class="category nav-link " href="../Controller/Controller.php?action=shopperHome&CATEGORYID=<?php echo $category->getCategoryID()?>&DESCRIPTION=<?php echo htmlspecialchars($category->getCategoryDescription())?>&Display=<?php echo 'category' ?>"><?php echo htmlspecialchars($category->getCategoryDescription()) ?></a>
                                </li>
                                <?php
                            }
                            ?>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-10 col-xs-12">
                    <div class="container-fluid">
                        <h3 class="category-heading"><?php echo htmlspecialchars($CategoryHeader)?></h3>
                        <div class="row">
                            <?php foreach ($ProductArray as $product) {
                                ?>
                            <div class="col-sm-6 col-md-4 col-lg-4 col-xl-2 py-3">
                                <div class="card h-100">
                                    <div class="card-header">
                                        <img src="https://dummyimage.com/256x256/000/fff.jpg" class="card-img-top" alt="...">
                                    </div>
                                    <div class="card-body">
                                        <h4 class="card-title"><?php echo htmlspecialchars($product->getProductName())?></h4>
                                    </div>
                                    <div class="card-footer">
                                        <form id="card-form" action="../controller/controller.php?action=processAddToCart&ProductID=<?php echo $product->getProductID()?>" method="post" enctype="multipart/form-data">
                                            <div class="form-group row flex-column" id="shopperAddToCartButton">
                                                <input type="number" id="quantity_<?php echo htmlspecialchars($product->getProductID())?>" name="QTYRequested" min="1" max="<?php echo htmlspecialchars($ProductRow['QTYONHAND'])?>">
                                                <input type="submit" class="btn btn-primary" value="Add To Cart">
                                            </div>
                                        </form>
                                        <script>
                                            document.getElementById("quantity_<?php echo htmlspecialchars($product->getProductID())?>").defaultValue = "1"
                                        </script>
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