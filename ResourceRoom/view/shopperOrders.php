<?php
    $title = " Shopper Orders Page";
    require '../view/headerInclude.php';
?>
<html>
<body>
    <section class="clarion-blue">
        <div class = "table-responsive">
            <table class="table clarion-blue" id="shopperOrderTable" style="border-collapse:collapse;">
                <thead>
                <tr class="clarion-white">
                    <th>STATUS</th>
                    <th>DATE ORDERED</th>
                    <th>DATE FILLED</th>
                    <th>DATE COMPLETED</th>
                    <th>NUMBER OF ITEMS</th>
                    <th>COMMENT</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach($orders as $order)
                {
                ?>
                        <tr data-toggle="collapse" data-target="#orderDetails_<?php echo $order->getOrderID()?>" class="accordion-toggle clarion-white">
                            <td><?php echo htmlspecialchars($order->getOrderStatus())?></td>
                            <td><?php $dateOrdered = new DateTime(htmlspecialchars($order->getOrderDateOrdered())); echo $dateOrdered->format('m/d/Y');?></td>
                            <td><?php if($order->getOrderDateFilled() && $order->getOrderDateFilled() != '0000-00-00'){ $dateOrderFilled = new DateTime(htmlspecialchars($order->getOrderDateFilled())); echo $dateOrderFilled->format('m/d/Y');}?></td>
                            <td><?php if($order->getOrderDateCompleted() && $order->getOrderDateCompleted() != '0000-00-00' ){ $dateOrderCompleted = new DateTime(htmlspecialchars($order->getOrderDateCompleted())); echo $dateOrderCompleted->format('m/d/Y');}?></td>
                            <td><?php echo htmlspecialchars($order->getOrderSize())?></td>
                            <td style="text-align: left;"><?php echo htmlspecialchars($order->getOrderComment())?></td>
                        </tr>
                        <tr class="color-black">
                            <td colspan="6" class="hiddenRow">
                                    <div class="accordian-body collapse" id="orderDetails_<?php echo $order->getOrderID()?>">
                                        <div class="table-responsive clarion-blue">
                                            <table class="color-grey table table-orderDetails">
                                                <thead>
                                                <tr>
                                                    <th>Product Name</th>
                                                        <?php
                                                            $orderDescriptionFlag = False;
                                                            foreach($order->getOrderDetails() as $orderDetail)
                                                            {
                                                                if($orderDetail->getProduct()->getProductDescription() != ""){
                                                                    $orderDescriptionFlag = True;
                                                                }
                                                            }
                                                        ?>
                                                        <?php if($orderDescriptionFlag):?>
                                                            <th>Description</th>
                                                        <?php endif;?>
                                                    <th>Quantity Requested</th>
                                                    <th>Quantity Filled</th>
                                                </tr>
                                                </thead>
                                                <?php foreach($order->getOrderDetails() as $orderDetail)
                                                {
                                                    ?>
                                                    <tr>
                                                        <td><?php echo htmlspecialchars($orderDetail->getProduct()->getProductName())?></td>
                                                        <?php if($orderDescriptionFlag):?>
                                                            <td><?php echo htmlspecialchars($orderDetail->getProduct()->getProductDescription())?></td>
                                                        <?php endif;?>
                                                        <td><?php echo htmlspecialchars($orderDetail->getQTYRequested())?></td>
                                                        <td><?php echo htmlspecialchars($orderDetail->getQTYFilled())?></td>
                                                    </tr>
                                                <?php   } ?>
                                                <?php if($order->getOrderStatus() != "COMPLETED"):?>
                                                <tr>
                                                    <td colspan="0">
                                                        <form action="../controller/controller.php?action=deleteOrder" onsubmit="return confirm('Are you sure you want to delete this order?');" method="post" enctype="multipart/form-data">
                                                            <div class="form-group row">
                                                                <input type='hidden' name='ORDERID' value='<?php echo $order->getOrderID()?>'/>
                                                                <input type="submit" class="btn btn-danger" style="margin-right: 25px" value="Delete Order">
                                                            </div>
                                                        </form>
                                                    </td>
                                                </tr>
                                                <?php else:?>
                                                <?php endif; ?>
                                            </table>
                                        </div>
                                </div>
                            </td>
                        </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </section>
</body>
</html>

<?php
    require '../view/footerInclude.php';
?>