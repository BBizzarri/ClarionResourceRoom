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
                                    <a class="category nav-link " href="../controller/controller.php?action=shopperHome&CATEGORYID=<?php echo $category->getCategoryID()?>&DESCRIPTION=<?php echo htmlspecialchars($category->getCategoryDescription())?>&Display=<?php echo 'category' ?>"><?php echo htmlspecialchars($category->getCategoryDescription()) ?></a>
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
                                                src="../productImages/ImageNotAvailable.jpg"
                                             <?php endif ;?>
                                                class="card-img-top" alt="...">
                                    </div>
                                    <div class="card-body">
                                        <h4 class="card-title"><?php echo htmlspecialchars($product->getProductName())?></h4>
                                        <?php if($product->getProductDescription() != ""): ?>
                                            <div>
                                                <button type="button" class="btn btn-light" data-toggle="modal" data-target="#modal_<?php echo $product->getProductID()?>">
                                                    Description
                                                </button>
                                            </div>
                                            <!-- Modal -->
                                            <div class="modal fade" id="modal_<?php echo $product->getProductID()?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Description:</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <?php echo htmlspecialchars($product->getProductName())?>
                                                            <?php echo htmlspecialchars($product->getProductDescription())?>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endif ; ?>
                                    </div>
                                    <?php if ($cart->inCart($product->getProductID())) : ?>
                                        <div class="card-footer">
                                            <h5>PRODUCT IN CART</h5>
                                        </div>
                                    <?php elseif($product->getProductQTYAvailable() > 0) :?>
                                        <div class="card-footer">
                                            <form id="card-form" action="../controller/controller.php?action=processAddToCart&ProductID=<?php echo $product->getProductID()?>" method="post" enctype="multipart/form-data">
                                                <div class="form-group row" id="shopperAddToCartButton">
                                                    <label class="col-4"for="quantity_<?php echo htmlspecialchars($product->getProductID())?>">QTY:</label>
                                                    <input class="col-6" type="number" id="quantity_<?php echo htmlspecialchars($product->getProductID())?>" name="QTYRequested" min="1" max="<?php echo htmlspecialchars($product->getProductOrderLimit())?>">
                                                </div>
                                                <div class="form-group row flex-column">
                                                    <input type="submit" class="btn btn-primary" value="Add To Cart">
                                                </div>
                                            </form>
                                            <script>
                                                document.getElementById("quantity_<?php echo htmlspecialchars($product->getProductID())?>").defaultValue = "1"
                                            </script>
                                        </div>
                                    <?php elseif($product->getProductGoalStock() > 0) : ?>
                                        <div class="card-footer">
                                            <h5>WILL BE RESTOCKED</h5>
                                        </div>
                                    <?php else : ?>
                                        <div class="card-footer">
                                            <h5>Unstocked Product</h5>
                                        </div>
                                    <?php endif;?>
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