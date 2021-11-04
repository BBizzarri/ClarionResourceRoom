<?php
    $title = " Shopper Orders Page";
    require '../view/headerInclude.php';
?>
<html>
<body>
    <section>
        <table class="table table-condensed clarion-blue" style="border-collapse:collapse;">
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
                        <td><?php echo htmlspecialchars($order->getOrderDateOrdered())?></td>
                        <td><?php echo htmlspecialchars($order->getOrderDateFilled())?></td>
                        <td><?php echo htmlspecialchars($order->getOrderDateCompleted())?></td>
                        <td><?php echo htmlspecialchars($order->getOrderSize())?></td>
                        <td><?php echo htmlspecialchars($order->getOrderComment())?></td>
                    </tr>
                    <tr class="color-black">
                        <td colspan="6" class="hiddenRow">
                                <div class="accordian-body collapse" id="orderDetails_<?php echo $order->getOrderID()?>">
                                    <div class="table-responsive clarion-blue">
                                        <table class="color-grey table table-orderDetails">
                                            <thead>
                                            <tr>
                                                <th>Product Name</th>
                                                <th>Description</th>
                                                <th>Quantity Request</th>
                                                <th>Quantity Filled</th>
                                            </tr>
                                            </thead>
                                            <?php foreach($order->getOrderDetails() as $orderDetail)
                                            {
                                                ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($orderDetail->getProduct()->getProductName())?></td>
                                                    <td><?php echo htmlspecialchars($orderDetail->getProduct()->getProductDescription())?></td>
                                                    <td><?php echo htmlspecialchars($orderDetail->getQTYRequested())?></td>
                                                    <td><?php echo htmlspecialchars($orderDetail->getQTYFilled())?></td>
                                                </tr>
                                            <?php   } ?>
                                        </table>
                                    </div>
                            </div>
                        </td>
                    </tr>
            <?php } ?>
            </tbody>
        </table>
    </section>
</body>
</html>
<?php
    require '../view/footerInclude.php';
?>