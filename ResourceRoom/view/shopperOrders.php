<?php
    $title = " Shopper Orders Page";
    require '../view/headerInclude.php';
?>
<html>
<body>
    <section class="clarion-blue">
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
                        <td><?php $dateOrdered = new DateTime(htmlspecialchars($order->getOrderDateOrdered())); echo $dateOrdered->format('m/d/Y');?></td>
                        <td><?php if($order->getOrderDateFilled()){ $dateOrderFilled = new DateTime(htmlspecialchars($order->getOrderDateFilled())); echo $dateOrderFilled->format('m/d/Y');}?></td>
                        <td><?php if($order->getOrderDateCompleted()){ $dateOrderCompleted = new DateTime(htmlspecialchars($order->getOrderDateCompleted())); echo $dateOrderCompleted->format('m/d/Y');}?></td>
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