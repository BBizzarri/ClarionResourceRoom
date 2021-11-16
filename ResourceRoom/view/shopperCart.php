<?php
    $title = " Shopping Cart Page";
    require '../view/headerInclude.php';
?>
<html>
<body>
    <section id="main" class="clarion-blue">
        <div class="container-fluid">
            <div class ="row">
                <div class="col-12">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="text-left clarion-white col-auto">
                                        <h2>Your Shopping Cart</h2>
                                </div>
                            </div>
                            <div class = "row">
                                <div class="text-right clarion-white col-auto">
                                        <h3>Items in cart: <?php echo($cart->getNumberOfItemsInCart()) ?><?php ?></h3>
                                </div>
                            </div>
                            <div class="row">
                                <?php foreach ($cart->getProductsInCart() as $cartItem)
                                {
                                    $product = $cartItem->getProductObject();
                                    ?>
                                    <div class="col-sm-6 col-md-4 col-lg-4 col-xl-2 py-3">
                                        <div class="card h-100">
                                            <div class="card-header">
                                                <img <?php if(file_exists("../productImages/{$product->getProductID()}.jpg")):?>
                                                    src="../productImages/<?php echo($product->getProductID())?>.jpg"
                                                <?php else :?>
                                                    src="../productImages/ImageNotAvailable.jpg"
                                                <?php endif ;?>
                                                        class="card-img-top productImg" alt="...">
                                            </div>
                                            <div class="card-body">
                                                <h4 class="card-title"><?php echo htmlspecialchars($product->getProductName()) ?></h4>
                                            </div>
                                            <div class="card-footer d-flex flex-column">
                                                <form id="card-form" action="../controller/controller.php?action=shopperAdjustQTYInCart&ProductID=<?php echo htmlspecialchars($product->getProductID())?>" method="post" enctype="multipart/form-data">
                                                    <div class="form-group row flex-column" id="shopperRemoveFromCartButton">
                                                        <div class="container">
                                                            <div class = "row">
                                                                <label class= "col-auto" for="quantity_<?php echo htmlspecialchars($product->getProductID())?>">QTY:</label>
                                                                <input class= "col-auto" type="number" id="quantity_<?php echo htmlspecialchars($product->getProductID())?>" name="QTYRequested" min="1" max="<?php echo htmlspecialchars($product->getProductOrderLimit())?>">
                                                                <input type="submit" class="btn btn-primary col-auto" value="Change Quantity">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                                <form id="card-form" action="../controller/controller.php?action=shopperRemoveFromCart&ProductID=<?php echo htmlspecialchars($product->getProductID())?>" method="post" enctype="multipart/form-data">
                                                    <div class="form-group row flex-column">
                                                        <input type="submit" class="btn btn-danger" value="Remove From Cart">
                                                    </div>
                                                </form>
                                                <script>
                                                    document.getElementById("quantity_<?php echo htmlspecialchars($product->getProductID())?>").defaultValue = "<?php echo htmlspecialchars($cartItem->getQTYRequested())?>"
                                                </script>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                }
                                ?>
                            </div>
                            <div class="row">
                                <div class="col-10">
                                </div>
                                <div class="col-2">
                                    <button style="float: right" type="button" class="btn btn-primary" data-toggle="modal" data-target="#cartModal">
                                        Submit Order
                                    </button>
                                </div>
                            </div>
                        </div>
                </div>
            </div>
        </div>


        <div class="modal fade" id="cartModal" role="dialog">
            <div class="modal-dialog modal-lg">


                <div class="modal-content clarion-blue clarion-white">
                    <div class="modal-header" style="border-bottom: 1px solid #97824A;">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                        <div class="row modal-body">
                            <div class="table-responsive">
                                <table class="table table-orderDetails">
                                    <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Quantity Requested</th>
                                        <?php if(userIsAuthorized("adminInventory")):?>
                                        <th>Order Limit</th>
                                        <?php endif; ?>
                                    </tr>
                                    </thead>
                                        <?php
                                        $cartError = FALSE;
                                        $cartErrorMessage = [];
                                        foreach ($cart->getProductsInCart() as $cartItem)
                                        {
                                        $product = $cartItem->getProductObject();
                                        if(($cartItem->getQTYRequested() > $product->getProductOrderLimit())){
                                            $cartError = TRUE;
                                            array_push($cartErrorMessage, [$product->getProductName(),$cartItem->getQTYRequested(),$product->getProductOrderLimit()]);
                                        }
                                        ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($product->getProductName())?></td>
                                                <td><?php echo htmlspecialchars($cartItem->getQTYRequested())?></td>
                                                <?php if(userIsAuthorized("adminInventory")):?>
                                                    <td><?php echo htmlspecialchars($product->getProductOrderLimit())?></td>
                                                <?php endif; ?>
                                            </tr>
                                <?php
                            }
                            ?>
                                </table>
                            </div>
                        </div>
                        <form>
                            <div>

                            </div>
                        </form>
                    <div class="modal-footer">
                        <div class="container">
                            <?php if($cartError){
                                echo '<h5>Quantity requested greater than quantity available, please change your order amount.</h5>';
                                foreach($cartErrorMessage as $message) {
                                    echo $message[0] . ' available: ' . $message[2];
                                    echo '<br>';
                                }
                            } ?>

                            <form action="../controller/controller.php?action=shopperSubmitOrder" method="post" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label for="cartComment">Special Order Instructions</label>
                                    <textarea class="form-control" rows="5" id="cartComment" name="cartComment"></textarea>
                                </div>
                                <div class="form-group row">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                <?php if(!$cartError and $cart->getNumberOfItemsInCart() > 0): ?>
                                <input type="submit" class="btn btn-primary" style="margin-right: 25px" value="Submit Order">
                                <?php endif ; ?>
                                    <h5>An order confirmation email will be sent.</h5>
                                </div>
                            </form>
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