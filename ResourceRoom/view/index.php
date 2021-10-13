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
                                        <img <?php if(file_exists("../productImages/{$product->getProductID()}.jpg")):?>
                                                src="../productImages/<?php echo($product->getProductID())?>.jpg"
                                             <?php else :?>
                                                src="https://dummyimage.com/256x256/000/fff.jpg"
                                             <?php endif ;?>
                                                class="card-img-top" alt="..." data-toggle="modal" data-target="#productModal_<?php echo $product->getProductID()?>">
                                    </div>
                                    <div class="card-body" data-toggle="modal" data-target="#productModal_<?php echo $product->getProductID()?>">
                                        <h4 class="card-title" data-toggle="modal" data-target="#productModal_<?php echo $product->getProductID()?>"><?php echo htmlspecialchars($product->getProductName())?></h4>
                                    </div>
                                    <?php if ($cart->inCart($product->getProductID())) : ?>
                                        <div class="card-footer">
                                            <h4>PRODUCT IN CART</h4>
                                        </div>
                                    <?php elseif($product->getProductQTYAvailable() > 0) :?>
                                        <div class="card-footer">
                                            <form id="card-form" action="../controller/controller.php?action=processAddToCart&ProductID=<?php echo $product->getProductID()?>" method="post" enctype="multipart/form-data">
                                                <div class="form-group row flex-column" id="shopperAddToCartButton">
                                                    <input type="number" id="quantity_<?php echo htmlspecialchars($product->getProductID())?>" name="QTYRequested" min="1" max="<?php echo htmlspecialchars($product->getProductOrderLimit())?>">
                                                    <input type="submit" class="btn btn-primary" value="Add To Cart">
                                                </div>
                                            </form>
                                            <script>
                                                document.getElementById("quantity_<?php echo htmlspecialchars($product->getProductID())?>").defaultValue = "1"
                                            </script>
                                        </div>
                                    <?php elseif($product->getProductGoalStock() > 0) : ?>
                                        <div class="card-footer">
                                            <h4>WILL BE RESTOCKED</h4>
                                        </div>
                                    <?php else : ?>
                                        <div class="card-footer">
                                            <h4>Unstocked Product</h4>
                                        </div>
                                    <?php endif;?>
                                </div>
                            </div>
                                <!-- Modal -->
                                <div class="modal fade" id="productModal_<?php echo $product->getProductID()?>" role="dialog">
                                    <div class="modal-dialog modal-lg">-->

                                        <!-- Modal content-->
                                        <div class="modal-content clarion-blue clarion-white">
                                            <div class="modal-header" style="border-bottom: 1px solid #97824A;">
                                                <h3><?php echo htmlspecialchars($product->getProductName())?></h3>
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            </div>
                                                <div class="row modal-body">
                                                    <div class="column product-info-left">
                                                        <h4>Description:</h4>
                                                        <p><?php echo htmlspecialchars($product->getProductDescription())?></p>
                                                    </div>
                                                    <div class="column product-info-right">
                                                        <img class="product-info-spacing" <?php if(file_exists("../productImages/{$product->getProductID()}.jpg")):?>
                                                        src="../productImages/<?php echo($product->getProductID())?>.jpg"
                                                            <?php else :?>
                                                                src="https://dummyimage.com/256x256/000/fff.jpg"
                                                            <?php endif ;?>>
                                                    </div>
                                                </div>
                                            <div class="modal-footer" style="border-top: 1px solid #97824A;">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                            </div>
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