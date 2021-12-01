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
                    <form id="ReportsSelect" action="../controller/controller.php?action=adminReports" method="post" enctype="multipart/form-data">
                        <label for="report">Report Type:</label><br>
                        <select class="sidebar-dropdown" name="report" id="report">
                            <option name="Users" value="Users" <?php if($_POST['report'] == 'Users'){ echo 'selected'; }?>>Users</option>
                            <option name="Orders" value="Orders" <?php if($_POST['report'] == 'Orders'){ echo 'selected'; }?>>Orders</option>
                            <option name="Products" value="Products" <?php if($_POST['report'] == 'Products'){ echo 'selected'; }?>>Products</option>
                        </select>
                        <!--<div id="date-picker-example">-->
                            <label for="example">Start Date: </label>
                            <input type="date" value= "0001-01-01" id="startDate" name="startDate">
                            <label for="example">End Date: </label>
                            <input type="date" value="<?php echo date("Y-m-d")?>" id="endDate" name="endDate" >
                        <!--</div>-->
                        <!--<div>-->
                            <label for="OnlyOrderedProducts" title="Only shows products that have been ordered during time period">Only Ordered Products</label>
                            <input type="hidden" value = '0'  name ="OnlyOrderedProducts"/>
                            <input type="checkbox" id="OnlyOrderedProducts" name="OnlyOrderedProducts"/>
                        <!--</div>-->
                        <!--<div>-->
                            <!--<label for="categorySelectReports">Categories</label>-->
                            <!--<select multiple class="category-list" size="<?php echo sizeof($CategoryArray) + 1; ?>" id="categorySelectReports" name="CategoryList[]" form="ReportsSelect">-->
                            <select name="CategoryList[]" id="categorySelectReports">
                                <option class="category nav-link col-12" style="white-space: normal" value="0">All</option>
                                <?php foreach ($CategoryArray as $category) {
                                    ?>
                                <option class="category nav-link col-12" style="white-space: normal" value="<?php echo $category->getCategoryID()?>"> <?php echo htmlspecialchars($category->getCategoryDescription())?></option>
                                <?php
                                }
                                ?>
                            </select>
                        <!--</div>-->
                          <input class="btn btn-secondary filter-button" type="submit" value="Apply"/>
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
                        header: true
                    }
                ]
            });
    } );
</script>
<?php
    require '../view/footerInclude.php';
?>