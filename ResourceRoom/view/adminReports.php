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
        <div class="row row-alignment">
            <div class="column sidebar">
                <div class="sidebar-elements">
                    <label for="report">Report Type:</label><br>
                    <select class="sidebar-dropdown" name="report" id="report">
                      <option value="Users">Users</option>
                    </select>
                </div>
            </div>
            <div class="column">
                <div class="table-heading date-selection">
                    <label class="clarion-white" for="startDate">Start Date:</label>
                    <input type="date" id="startDate" name="startDate">
                    <label class="clarion-white" for="endDate">End Date:</label>
                    <input type="date" id="endDate" name="endDate">
                </div>
                <div class="table-heading export-button">
                    <button type="button">Export</button>
                </div>
                <div class="container column column-spacing">
                    <!--This is where the reports will live-->
                    <table>
                                  <tr>
                                    <th>Last Name</th>
                                    <th>First Name</th>
                                    <th>Email</th>
                                    <th>Total Orders</th>
                                  </tr>
                                  <tr>
                                    <td class="text-left">Agens</td>
                                    <td class="text-left">Jason</td>
                                    <td class="text-left">j.y.agens@eagle.clarion.edu</td>
                                    <td>9</td>
                                  </tr>
                                  <tr>
                                    <td class="text-left">Andino</td>
                                    <td class="text-left">Carlos</td>
                                    <td class="text-left">c.e.adino@eagle.clarion.edu</td>
                                    <td>5</td>
                                  <tr>
                                    <td class="text-left">Bizzarri</td>
                                    <td class="text-left">Brady</td>
                                    <td class="text-left">b.m.bizzarri@eagle.clarion.edu</td>
                                    <td>15</td>
                                  </tr>
                                  </tr>
                                </table>
                </div>
            </div>
        </div>
    </section>
</body>
</html>
<?php
    require '../view/footerInclude.php';
?>