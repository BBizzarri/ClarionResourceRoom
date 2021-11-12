<?php
    $title = " Admin Reports Page";
    require '../view/headerInclude.php';
?>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <section class="clarion-blue">
        <div class="container-fluid">
            <div class="row">
                <div class="column sidebar">
                    <div class="col-auto sidebar">
                        <label for="report">Report Type:</label><br>
                        <select class="sidebar-dropdown" name="report" id="report">
                          <option value="Users">Users</option>
                        </select>
                    </div>
                </div>
                <div class="col-auto">
                    <div class="container-fluid clarion-white">
                        <!--<div class="table-heading date-selection">
                            <label class="clarion-white" for="startDate">Start Date:</label>
                            <input type="date" id="startDate" name="startDate">
                            <label class="clarion-white" for="endDate">End Date:</label>
                            <input type="date" id="endDate" name="endDate">
                        </div>
                        <div class="table-heading export-button">
                            <button type="button">Export</button>
                        </div>-->
                            <!--This is where the reports will live-->
                            <table>
                                          <tr>
                                            <th>User ID</th>
                                            <th>First Name</th>
                                            <th>Last Name</th>
                                            <th>Username</th>
                                            <th>Email</th>
                                            <th>Total Orders</th>
                                          </tr>
                                          <?php foreach($SelectedReport as $ReportRow) { ?>
                                              <tr>
                                                <td class="text-left"><?php echo $ReportRow['UserID'] ?></td>
                                                <td class="text-left"><?php echo $ReportRow['FirstName'] ?></td>
                                                <td class="text-left"><?php echo $ReportRow['LastName'] ?></td>
                                                <td class="text-left"><?php echo $ReportRow['UserName'] ?></td>
                                                <td class="text-left"><?php echo $ReportRow['Email'] ?></td>
                                                <td class="text-left"><?php echo $ReportRow['TotalOrders'] ?></td>
                                              </tr>
                                          <?php } ?>
                                        </table>
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