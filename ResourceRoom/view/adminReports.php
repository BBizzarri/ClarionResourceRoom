<?php
    $title = " Admin Reports Page";
    require '../view/headerInclude.php';
?>
<html>
<body>
    <section class="clarion-blue">
        <div class="container-fluid clarion-white">
            <div class="row">
                <div class="col-12">
                    <form id="ReportsSelect" action="../controller/controller.php?action=adminReports" method="post" enctype="multipart/form-data" style="padding-top: 5px">
                        <div class = 'form-row'>
                            <div class="col-auto">
                                <label for="report">Report Type:</label>
                                <select class="" name="report" id="report">
                                    <option name="Users" value="Users" <?php if(isset($_POST['report']) AND $_POST['report'] == 'Users'){ echo 'selected'; }?>>Users</option>
                                    <option name="Orders" value="Orders" <?php if(isset($_POST['report']) AND $_POST['report'] == 'Orders'){ echo 'selected'; }?>>All Orders</option>
                                    <option name="OrderTotals" value="OrderTotals" <?php if(isset($_POST['report']) AND $_POST['report'] == 'OrderTotals'){ echo 'selected'; }?>>Order Totals</option>
                                    <option name="Products" value="Products" <?php if(isset($_POST['report']) AND $_POST['report'] == 'Products'){ echo 'selected'; }?>>Products</option>
                                </select>
                                <label class="reports-nav" for="example">Start Date: </label>
                                <input type="date" value= "<?php if($_POST['startDate'])
                                                                 {
                                                                    echo $_POST['startDate'];
                                                                 }
                                                                 else
                                                                 {
                                                                    $currentDate = date('Y/m/d'); $final_date = date('Y-m-d', strtotime($currentDate.' -4 months'));
                                                                    echo $final_date;
                                                                 }?>"
                                id="startDate" name="startDate"
                                >
                                <label class="reports-nav" for="example">End Date: </label>
                                <input type="date" value="<?php echo date("Y-m-d")?>" id="endDate" name="endDate" >
                            </div>
                            <div id="productOnlyOptions" class="col-auto">
                                <label class="reports-nav" for="OnlyOrderedProducts" title="Only shows products that have been ordered during time period">Only Ordered Products</label>
                                <input type="hidden" value = '0'  name ="OnlyOrderedProducts"/>
                                <input type="checkbox" id="OnlyOrderedProducts" name="OnlyOrderedProducts"/>
                                <label class="reports-nav" for="categorySelectReports">Categories</label>
                                <select id="categorySelectReports" class="selectpicker" name="CategoryList[]" multiple form="ReportsSelect" required>
                                    <option class="category nav-link col-12" style="white-space: normal" value="0">All</option>
                                    <?php foreach ($CategoryArray as $category) {
                                        ?>
                                        <option class="category nav-link col-12" style="white-space: normal" value="<?php echo $category->getCategoryID()?>"> <?php echo htmlspecialchars($category->getCategoryDescription())?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <div id="allOrdersOnlyOptions" class="col-auto">
                                <label class="reports-nav" for="orderStatusSelectReports">Order Status</label>
                                <select id="orderStatusSelectReports" class="selectpicker" name="OrderStatusList[]" multiple form="ReportsSelect" required>
                                    <option class="category nav-link col-12" style="white-space: normal" value="ALL">ALL</option>
                                    <option class="category nav-link col-12" style="white-space: normal" value="SUBMITTED">SUBMITTED</option>
                                    <option class="category nav-link col-12" style="white-space: normal" value="READYFORPICKUP">READYFORPICKUP</option>
                                    <option class="category nav-link col-12" style="white-space: normal" value="COMPLETED">COMPLETED</option>
                                    <option class="category nav-link col-12" style="white-space: normal" value="USERCANCELLED">USERCANCELLED</option>
                                    <option class="category nav-link col-12" style="white-space: normal" value="ADMINCANCELLED">ADMINCANCELLED</option>
                                </select>
                            </div>
                            <div class="col-1">
                                <input class="btn btn-secondary filter-button reports-nav" type="submit" value="Apply"/>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="container-fluid clarion-white">
                        <div class = 'table-responsive'>
                            <table class="table table-striped inventoryTable" id="reportsTable">
                                <thead>
                                <tr>
                                    <?php foreach(array_keys($SelectedReport[0]) as $TableHeader) { ?>
                                        <th><?php echo $TableHeader ?></th>
                                    <?php } ?>
                                </tr>
                                </thead>
                                <?php foreach($SelectedReport as $ReportRow) { ?>
                                    <tr>
                                        <?php foreach(array_keys($SelectedReport[0]) as $TableHeader) {
                                            if ($TableHeader == 'DATEORDERED' or $TableHeader == 'DATEFILLED' or $TableHeader == 'DATECOMPLETED') {
                                                $date = toDisplayDate($ReportRow[$TableHeader]);
                                                echo "<td class='text-left'>$date</td>";
                                            }
                                            else{
                                                $output = htmlspecialchars($ReportRow[$TableHeader]);
                                                echo "<td class='text-left'>$output</td>";
                                            }
                                        }
                                        ?>
                                    </tr>
                                <?php } ?>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>
</html>
 <script>
    $(document).ready(function()
    {
        $("#reportsTable").DataTable(
            {
                searching: true,
                "pageLength" : 100,
                "lengthChange": true,
                "lengthMenu": [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
                dom:'Blfrtip',
                buttons: [
                    {
                        extend: 'csvHtml5',
                        text: 'Excel',
                        header: true,

                    }
                ]
            });
    } );

    $("#report").change(function() {
        if ($(this).val() == "Products") {
            $('#productOnlyOptions').show();
            $('#categorySelectReports').attr('required', '');
        } else {
            $('#productOnlyOptions').hide();
            $('#categorySelectReports').removeAttr('required');
        }
        if ($(this).val() == "Orders") {
            $('#allOrdersOnlyOptions').show();
            $('#orderStatusSelectReports').attr('required', '');
        } else {
            $('#allOrdersOnlyOptions').hide();
            $('#orderStatusSelectReports').removeAttr('required');
        }
    });
    $("#report").trigger("change");
</script>
<?php
    require '../view/footerInclude.php';
?>