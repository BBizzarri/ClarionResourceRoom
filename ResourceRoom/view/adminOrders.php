<?php
    $title = " Admin Orders Page";
    require '../view/headerInclude.php';
?>
    <html>
    <body>
    <section class="clarion-blue">
        <div class="container-fluid clarion-white">
            <div class="row">
                <div class="col-lg-4">
                    <h3 class="text-center">SUBMITTED ORDERS</h3>
                    <table class="table table-condensed" style="border-collapse:collapse;">
                        <thead>
                        <tr>
                            <!--<th>STATUS</th>-->
                            <th>Name</th>
                            <th>DATE ORDERED</th>
                            <!--<th>DATE FILLED</th>
                            <th>DATE COMPLETED</th>-->
                            <th>NUMBER OF ITEMS</th>
                            <!--<th>COMMENT</th>-->
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach($AllOrders as $order)
                        {
                            if($order->getOrderStatus() == "SUBMITTED")
                            {
                                ?>
                                <tr data-toggle="modal" data-target="#orderDetails_<?php echo $order->getOrderID()?>">
                                    <td><?php echo htmlspecialchars($order->getUsersName())?></td>
                                    <!--<td><?php echo htmlspecialchars($order->getOrderStatus())?></td>-->
                                    <td><?php $dateOrdered = new DateTime(htmlspecialchars($order->getOrderDateOrdered())); echo $dateOrdered->format('m/d/Y');?></td>
                                    <!--<td><?php echo htmlspecialchars($order->getOrderDateFilled())?></td>
                                    <td><?php echo htmlspecialchars($order->getOrderDateCompleted())?></td>-->
                                    <td><?php echo htmlspecialchars($order->getOrderSize())?></td>
                                    <!--<td><?php echo htmlspecialchars($order->getOrderComment())?></td>-->
                                </tr>

                            <?php } }?>
                        </tbody>
                    </table>
                </div>
                <div class="col-lg-4">
                    <h3 class="text-center">READY FOR PICKUP</h3>
                    <table class="table table-condensed" style="border-collapse:collapse;">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <!--<th>STATUS</th>-->
                            <!--<th>DATE ORDERED</th>-->
                            <th>DATE FILLED</th>
                            <!--<th>DATE COMPLETED</th>-->
                            <th>NUMBER OF ITEMS</th>
                            <!--<th>COMMENT</th>-->
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach($AllOrders as $order)
                        {
                            if($order->getOrderStatus() == "READY FOR PICKUP")
                            {
                                ?>
                                <tr data-toggle="modal" data-target="#orderDetails_<?php echo $order->getOrderID()?>">
                                    <td><?php echo htmlspecialchars($order->getUsersName())?></td>
                                    <!--<td><?php echo htmlspecialchars($order->getOrderStatus())?></td>-->
                                    <!--<td><?php echo htmlspecialchars($order->getOrderDateOrdered())?></td>-->
                                    <td><?php $dateFilled = new DateTime(htmlspecialchars($order->getOrderDateFilled())); echo $dateFilled->format('m/d/Y'); ?></td>
                                    <!--<td><?php echo htmlspecialchars($order->getOrderDateCompleted())?></td>-->
                                    <td><?php echo htmlspecialchars($order->getOrderSize())?></td>
                                    <!--<td><?php echo htmlspecialchars($order->getOrderComment())?></td>-->
                                </tr>
                            <?php } }?>
                        </tbody>
                    </table>
                </div>
                <div class="col-lg-4">
                    <h3 class="text-center">COMPLETED ORDERS</h3>
                    <table class="table table-condensed clarion-white" style="border-collapse:collapse;">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <!--<th>STATUS</th>-->
                            <!--<th>DATE ORDERED</th>
                            <th>DATE FILLED</th>-->
                            <th>DATE COMPLETED</th>
                            <th>NUMBER OF ITEMS</th>
                            <!--<th>COMMENT</th>-->
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach($AllOrders as $order)
                        {
                            if($order->getOrderStatus() == "COMPLETED")
                            {
                                ?>
                                <tr data-toggle="modal" data-target="#orderDetails_<?php echo $order->getOrderID()?>">
                                    <td><?php echo htmlspecialchars($order->getUsersName())?></td>
                                    <!--<td><?php echo htmlspecialchars($order->getOrderStatus())?></td>-->
                                    <!--<td><?php echo htmlspecialchars($order->getOrderDateOrdered())?></td>
                                    <td><?php echo htmlspecialchars($order->getOrderDateFilled())?></td>-->
                                    <td><?php $dateCompleted = new DateTime(htmlspecialchars($order->getOrderDateCompleted())); echo $dateCompleted->format('m/d/Y');?></td>
                                    <td><?php echo htmlspecialchars($order->getOrderSize())?></td>
                                    <!--<td><?php echo htmlspecialchars($order->getOrderComment())?></td>-->
                                </tr>
                            <?php } }?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php foreach($AllOrders as $order)
        {
            ?>
            <!-- Individual order Modal -->
            <div class="modal fade" id="orderDetails_<?php echo $order->getOrderID()?>" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable orderModals">
                    <div class="modal-content clarion-blue clarion-white">
                        <div class="modal-header">
                            <h5 class="modal-title" id="staticBackdropLabel_<?php echo $order->getOrderID()?>"><?php echo htmlspecialchars($order->getOrderStatus()) . ':' . ' ' . htmlspecialchars($order->getUsersName())?></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="order_<?php echo $order->getOrderID()?>"
                                <?php if($order->getOrderStatus() == "SUBMITTED"): ?>
                                action="../controller/controller.php?action=adminFillOrder&orderID=<?php echo $order->getOrderID()?>&status=<?php echo $order->getOrderStatus()?>"
                                 <?php else:?>
                                action="../controller/controller.php?action=adminChangeOrderStatus&orderID=<?php echo $order->getOrderID()?>&status=<?php echo $order->getOrderStatus()?>"
                                <?php endif; ?>
                                method="post" enctype="multipart/form-data">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>Product Name</th>
                                    <?php
                                        $orderDescriptionFlag = False;
                                        foreach($order->getOrderDetails() as $orderDetail){
                                            if($orderDetail->getProduct()->getProductDescription() != ""){
                                                $orderDescriptionFlag = True;
                                            }
                                        }
                                    ?>
                                    <?php if($orderDescriptionFlag): ?>
                                        <th>Description</th>
                                    <?php endif; ?>
                                    <th>Quantity Requested</th>
                                    <th>Quantity Filled</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach($order->getOrderDetails() as $orderDetail)
                                {
                                    ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($orderDetail->getProduct()->getProductName())?></td>
                                        <?php if($orderDescriptionFlag): ?>
                                            <td><?php echo htmlspecialchars($orderDetail->getProduct()->getProductDescription());?></td>
                                        <?php endif; ?>
                                        <td><?php echo htmlspecialchars($orderDetail->getQTYRequested())?></td>
                                    <?php if($order->getOrderStatus() == "SUBMITTED"): ?>
                                        <td><input type="number" min="0" name="<?php echo $orderDetail->getProductID()?>" value="<?php echo htmlspecialchars($orderDetail->getQTYRequested())?>" required/></td>
                                    <?php else:?>
                                        <td><?php echo htmlspecialchars($orderDetail->getQTYFilled())?></td>
                                    <?php endif; ?>
                                    </tr>
                                <?php   } ?>
                                <?php if($order->getOrderComment() != ""): ?>
                                    <tr>
                                        <td>
                                            <h5>Comments:</h5>
                                        </td>
                                        <td
                                        <?php if($orderDescriptionFlag){
                                            echo 'colspan="3"';
                                        }else{
                                            echo 'colspan="2"';
                                        }
                                        ?>
                                        >
                                            <p style="text-align: left"><?php echo htmlspecialchars($order->getOrderComment())?></p>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="modal-footer">
                            <?php if($order->getOrderStatus() == "SUBMITTED"): ?>
                                <input class="additional-comments" type="text" name="fillerComments" id="fillerComments" placeholder="Additional Comments"/>
                                <button type="submit" class="btn btn-success">Fill Order</button>
                                </form>
                                <form action="../controller/controller.php?action=deleteOrder" method="post" enctype="multipart/form-data">
                                    <input type='hidden' name='ORDERID' value='<?php echo $order->getOrderID()?>'/>
                                    <input type="submit" class="btn btn-danger" style="margin-right: 25px" value="Delete Order">
                                </form>
                            <?php elseif ($order->getOrderStatus() == "READY FOR PICKUP"):?>
                                <button type="submit" class="btn btn-success">Order Picked Up</button>
                                <button type="button" class="btn btn-warning" onclick="reNotify(<?php echo $order->getOrderID();?>);">Re-Notify</button>
                                </form>
                                <form action="../controller/controller.php?action=deleteOrder" method="post" enctype="multipart/form-data">
                                    <input type='hidden' name='ORDERID' value='<?php echo $order->getOrderID()?>'/>
                                    <input type="submit" class="btn btn-danger" style="margin-right: 25px" value="Delete Order">
                                </form>
                            <?php elseif ($order->getOrderStatus() == "COMPLETED"):?>
                                </form>
                            <?php endif; ?>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            </div>
        <?php  }?>
    </section>
    </body>
    </html>
<?php
    require '../view/footerInclude.php';
?>