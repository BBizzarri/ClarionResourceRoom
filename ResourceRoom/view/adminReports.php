<?php
    $title = " Admin Reports Page";
    require '../view/headerInclude.php';
?>
<html>
<body>
    <section class="clarion-blue">
        <div class="container-fluid clarion-white">
            <div class="row">
                <div class="col-3">
                    <form id="ReportsSelect" action="../controller/controller.php?action=adminReports" method="post" enctype="multipart/form-data">
                          <label for="report">Report Type:</label><br>
                          <select class="sidebar-dropdown" name="report" id="report">
                            <option name="Users" value="Users">Users</option>
                            <option name="Orders" value="Orders">Orders</option>
                            <option name="Products" value="Products">Products</option>
                          </select>
                          <input class="btn btn-secondary filter-button" type="submit" value="Apply"/>
                    </form>
                </div>

            <div class="col-9">
                <form id="ReportsFilter" action="#" method="post" enctype="multipart/form-data">
                      <div id="date-picker-example" class="md-form md-outline input-with-post-icon datepicker" inline="true">
                            <label for="example">Start Date: </label>
                            <input placeholder="Select date" id="startDate" name="startDate" type="date">
                            <label for="example">End Date: </label>
                            <input placeholder="Select date" id="endDate" name="endDate" type="date">
                            <input class="btn btn-secondary filter-button" type="submit" value="Apply"/>
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
                                                echo "<td class='text-left'>$ReportRow[$TableHeader]</td>";
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
                dom:'Bfrtip',
                buttons: [
                    {
                        extend: 'csvHtml5',
                        header: true
                    }
                ]
            });
    } );
</script>
<?php
    require '../view/footerInclude.php';
?>