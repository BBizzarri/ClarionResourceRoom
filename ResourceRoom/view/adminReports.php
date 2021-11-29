<?php
    $title = " Admin Reports Page";
    require '../view/headerInclude.php';
?>
<html>
<body>
    <section class="clarion-blue">
        <div class="container-fluid">
            <div class="row">
                <div class="column sidebar">
                    <div class="col-12 sidebar">
                        <label for="report">Report Type:</label><br>
                        <select class="sidebar-dropdown" name="report" id="report">
                          <option value="Users">Users</option>
                        </select>
                    </div>
                </div>
                <div class="col-12">
                    <div class="container-fluid clarion-white">
                        <div class = 'table-responsive'>
                            <table id="reportsTable">
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