<?php
    $title = " Admin Orders Page";
    require '../view/headerInclude.php';
?>
    <section class="clarion-blue">
        <div class="container-fluid clarion-white">
            <div class="row">
                <div class="col">
                    <h3 class="text-center">SUBMITTED ORDERS</h3>
                    <table class="table table-condensed" id="submittedOrders" style="border-collapse:collapse;">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>DATE ORDERED</th>
                            <th>NUMBER OF ITEMS</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach($AllOrders as $order)
                        {
                            if($order->getOrderStatus() == "SUBMITTED")
                            {
                                ?>
                                <tr data-toggle="modal" data-target="#orderDetails_<?php echo $order->getOrderID()?>">
                                    <td class="text-left"><?php echo htmlspecialchars($order->getUsersName())?></td>
                                    <td><?php $dateOrdered = new DateTime(htmlspecialchars($order->getOrderDateOrdered())); echo $dateOrdered->format('m/d/Y');?></td>
                                    <td><?php echo htmlspecialchars($order->getOrderSize())?></td>
                                </tr>

                            <?php } }?>
                        </tbody>
                    </table>
                </div>
                <div class="col">
                    <h3 class="text-center">READY FOR PICKUP</h3>
                    <table class="table table-condensed" style="border-collapse:collapse;">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>DATE FILLED</th>
                            <th>NUMBER OF ITEMS</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach($AllOrders as $order)
                        {
                            if($order->getOrderStatus() == "READY FOR PICKUP")
                            {
                                ?>
                                <tr data-toggle="modal" data-target="#orderDetails_<?php echo $order->getOrderID()?>">
                                    <td class="text-left"><?php echo htmlspecialchars($order->getUsersName())?></td>
                                    <td><?php $dateFilled = new DateTime(htmlspecialchars($order->getOrderDateFilled())); echo $dateFilled->format('m/d/Y'); ?></td>
                                    <td><?php echo htmlspecialchars($order->getOrderSize())?></td>
                                </tr>
                            <?php } }?>
                        </tbody>
                    </table>
                </div>
                <div class="col">
                    <h3 class="text-center">COMPLETED ORDERS</h3>
                    <form id="AdminOrdersDatesSelect" action="../controller/controller.php?action=adminOrders" method="post" enctype="multipart/form-data" style="padding-top: 5px">
                        <label class="reports-nav" for="example">Orders Since: </label>
                        <input type="date" value="<?php if(isset($_POST['orderSince']))
                                                         {
                                                            $date = $_POST['orderSince'];
                                                         }
                                                         else
                                                         {
                                                            $currentDate = date('Y/m/d');
                                                            $date = date('Y-m-d', strtotime($currentDate.' -4 months'));
                                                         }
                                                         echo $date;?>"
                        id="orderSince" name="orderSince"
                        >
                        <input class="btn btn-secondary filter-button reports-nav" type="submit" value="Apply"/>
                    </form>
                    <table class="table table-condensed clarion-white" id="completedOrdersTable" style="border-collapse:collapse;">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>DATE COMPLETED</th>
                            <th>NUMBER OF ITEMS</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach($AllOrders as $order)
                        {
                            if($order->getOrderStatus() == "COMPLETED" && $order->getOrderDateCompleted() > $date)
                            {
                                ?>
                                <tr data-toggle="modal" data-target="#orderDetails_<?php echo $order->getOrderID()?>">
                                    <td class="text-left"><?php echo htmlspecialchars($order->getUsersName())?></td>
                                    <td><?php $dateCompleted = new DateTime(htmlspecialchars($order->getOrderDateCompleted())); echo $dateCompleted->format('m/d/Y');?></td>
                                    <td><?php echo htmlspecialchars($order->getOrderSize())?></td>
                                </tr>
                            <?php } }?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php if($order->getOrderStatus() != "USERCANCELLED" && $order->getOrderStatus() != "ADMINCANCELLED"){ foreach($AllOrders as $order)
        {
            ?>
            <!-- Individual order Modal -->
            <div class="modal fade" id="orderDetails_<?php echo $order->getOrderID()?>" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
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
                                <?php elseif($order->getOrderStatus() == "READY FOR PICKUP"): ?>
                                action="../controller/controller.php?action=adminChangeOrderStatus&orderID=<?php echo $order->getOrderID()?>&status=<?php echo $order->getOrderStatus()?>"
                                <?php else: ?>
                                action="../controller/controller.php?action=adminChangeOrderStatus&orderID=<?php echo $order->getOrderID()?>&status=<?php echo $order->getOrderStatus()?>"
                                <?php endif; ?>
                                method="post" enctype="multipart/form-data">
                                <div class ='table-responsive'>
                                    <table class="table" id="orderTable_<?php echo $order->getOrderID()?>">
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
                                                <td class="text-left"><?php echo htmlspecialchars($orderDetail->getProduct()->getProductName())?></td>
                                                <?php if($orderDescriptionFlag): ?>
                                                    <td class="text-left"><?php echo htmlspecialchars($orderDetail->getProduct()->getProductDescription());?></td>
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
                                                    <h5 class=text-left>Comments:</h5>
                                                </td>
                                                <td
                                                <?php if($orderDescriptionFlag){
                                                    echo 'colspan="2"';
                                                }else{
                                                    echo 'colspan="3"';
                                                }
                                                ?>
                                                >
                                                    <p style="text-align: left"><?php echo htmlspecialchars($order->getOrderComment())?></p>
                                                </td>
                                                <td style="display: none"></td>
                                            </tr>
                                        <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                        </div>
                        <script>
                            $(document).ready(function()
                            {
                                $("#orderTable_<?php echo $order->getOrderID()?>").DataTable(
                                    {
                                        searching: false,
                                        "ordering": false,
                                        "paging": false,
                                        dom:'Bfrtip',
                                        buttons: [
                                            {
                                                extend: 'csvHtml5',
                                                text: 'Excel',
                                                header: true
                                            },
                                            {
                                                extend: 'print',
                                                title: '<?php echo htmlspecialchars($order->getUsersName())  . ':' . ' ' . htmlspecialchars($order->getOrderStatus())?>',
                                            }
                                        ]
                                    });
                            } );
                        </script>
                        <div class="modal-footer">
                            <div class = container>
                                <?php if($order->getOrderStatus() == "SUBMITTED"): ?>
                                    <div class = 'row'>
                                        <div class = 'col-8'>
                                            <input class="w-100" type="text" name="fillerComments" id="fillerComments" placeholder="Additional Comments"/>
                                        </div>
                                        <div class = 'col-auto'>
                                            <button type="submit" class="btn btn-success">Fill Order</button>
                                            </form>
                                        </div>
                                        <div class = 'col-auto'>
                                            <form action="../controller/controller.php?action=deleteOrder" onsubmit="return confirm('Are you sure you want to cancel this order?');" method="post" enctype="multipart/form-data">
                                                <input type='hidden' name='ORDERID' value='<?php echo $order->getOrderID()?>'/>
                                                <input type="submit" class="btn btn-danger"  value="Cancel Order">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            </form>
                                        </div>
                                    </div>
                                <?php elseif ($order->getOrderStatus() == "READY FOR PICKUP"):?>
                                    <div class = 'row d-flex justify-content-end'>
                                        <div class = 'col-auto'>
                                            <button type="submit" class="btn btn-success">Order Picked Up</button>
                                            <button type="button" class="btn btn-warning" onclick="reNotify(<?php echo $order->getOrderID();?>);">Re-Notify</button>
                                            </form>
                                        </div>
                                        <div class = 'col-auto'>
                                            <form action="../controller/controller.php?action=deleteOrder" onsubmit="return confirm('Are you sure you want to cancel this order?');" method="post" enctype="multipart/form-data">
                                                <input type='hidden' name='ORDERID' value='<?php echo $order->getOrderID()?>'/>
                                                <input type="submit" class="btn btn-danger"   value="Cancel Order">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            </form>
                                        </div>
                                    </div>
                                <?php elseif ($order->getOrderStatus() == "COMPLETED"):?>
                                    </form>
                                    <div class = 'd-flex justify-content-end'>
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    </div>
                                <?php else: ?>
                                </form>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </div>
        <?php  }
        }?>
    </section>
    <script>
        $(document).ready(function()
        {
            $("#completedOrdersTable").DataTable(
                {
                    searching: true,
                    "pageLength" : -1,
                    dom:'Bfrtip',
                    buttons: [
                        {
                            extend: 'csvHtml5',
                            text: 'Excel',
                            header: true
                        },
                        {
                            extend: 'print',
                        }
                    ]
                });
        } );
    </script>
<?php
    require '../view/footerInclude.php';
?>