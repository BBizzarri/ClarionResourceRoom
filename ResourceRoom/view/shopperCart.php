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
                                <div class="text-left clarion-white" style="margin-left: 25px">
                                    <div style="margin-block: 15px">
                                        <h2>Your Shopping Cart</h2>
                                    </div>
                                </div>
                                <div class="text-right clarion-white" style="margin-left: 650px">
                                    <div style="margin-block: 15px">
                                        <h3>Total Items in cart: <?php echo($cart->getNumberOfItemsInCart()) ?><?php ?></h3>
                                    </div>
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
                                                        class="card-img-top" alt="...">
                                            </div>
                                            <div class="card-body">
                                                <h4 class="card-title"><?php echo htmlspecialchars($product->getProductName()) ?></h4>
                                            </div>
                                            <div class="card-footer d-flex flex-column">
                                                <form id="card-form" action="../controller/controller.php?action=shopperAdjustQTYInCart&ProductID=<?php echo htmlspecialchars($product->getProductID())?>" method="post" enctype="multipart/form-data">
                                                    <div class="form-group flex-row" id="shopperRemoveFromCartButton">
                                                        <label>QTY:</label>
                                                        <input type="number" id="quantity_<?php echo htmlspecialchars($product->getProductID())?>" name="QTYRequested" min="1" max="<?php echo htmlspecialchars($product->getProductQTYOnHand())?>">
                                                        <input type="submit" class="btn btn-primary" value="Change Quantity">
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
                            <div class="row" style="float: right">
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#cartModal">
                                    Submit Order
                                </button>
                            </div>
                        </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="cartModal" role="dialog">
            <div class="modal-dialog modal-lg">-->

                <!-- Modal content-->
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
                                        <th>Quantity Request</th>
                                        <th>Quantity Available</th>
                                    </tr>
                                    </thead>
                                        <?php
                                        $cartError = FALSE;
                                        foreach ($cart->getProductsInCart() as $cartItem)
                                        {
                                        $product = $cartItem->getProductObject();
                                        if($cartError == FALSE and ($cartItem->getQTYRequested() <=$product->getProductQTYAvailable())){
                                            $cartError = FALSE;
                                        }
                                        else{
                                            $cartError = TRUE;
                                        }
                                        ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($product->getProductName())?></td>
                                                <td><?php echo htmlspecialchars($cartItem->getQTYRequested())?></td>
                                                <td><?php echo htmlspecialchars($product->getProductQTYAvailable())?></td>
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
                            <?php if($cartError): ?>
                                <h3>Quantity requested greater than quantity available, please change your order amount.</h3>
                            <?php endif ; ?>
                            <form action="../controller/controller.php?action=shopperSubmitOrder" method="post" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label for="cartComment">Special Order Instructions</label>
                                    <textarea class="form-control" rows="5" id="cartComment" name="cartComment"></textarea>
                                </div>
                                <div class="form-group">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                <?php if(!$cartError and $cart->getNumberOfItemsInCart() > 0): ?>
                                <input type="submit" class="btn btn-primary" value="Submit Order">
                                <?php endif ; ?>
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