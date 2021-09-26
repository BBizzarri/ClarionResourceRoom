<?php
    $title = " Admin Inventory Page";
    require '../view/headerInclude.php';
?>
<html>
<body>
    <section class="clarion-blue">
        <div class="row row-alignment">
          <div class="column sidebar">
            <div class="sidebar-elements">
                <div class="incoming-textbox-div">
                    <h3 class="sidebar-heading">Filter Options</h3>
                    <label for="qtyLessThan"> Quantity Less Than:</label><br>
                </div>
                <div class="incoming-textbox-div">
                    <input class="incoming-textbox" type="number" id="qtyLessThan" name="qtyLessThan" value="qtyLessThan">
                </div>
            </div>
            <div class="sidebar-elements">
                <input type="checkbox" id="inactiveItems" name="inactiveItems" value="inactiveItems">
                <label for="inactiveItems"> Include Inactive Items</label><br>
            </div>
            <div>
                <button class="apply-button" type="button">Apply</button>
            </div>
            <hr class="hr-style">
            <div class="sidebar-elements">
                  <h3 class="sidebar-heading">Categories</h3>
                  <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="category nav-link" href="#"><div class="container-fluid">Shopping List</div></a>
                    </li>
                    <li class="nav-item">
                        <a class="category nav-link" href="#"><div class="container-fluid">All</div></a>
                    </li>
                    <?php foreach ($CategoryResults as $CategoryRow) {
                    ?>
                        <li class="nav-item">
                            <a class="category nav-link" href="../Controller/Controller.php?action=displaySelectedCategory&CATEGORYID=<?php echo $CategoryRow['CATEGORYID'] ?>"><?php echo htmlspecialchars($CategoryRow['DESCRIPTION']) ?></a>
                        </li>
                    <?php
                    }
                    ?>
                  </ul>
            </div>
          </div>
          <div class="container column column-spacing">
                <div class=" clarion-white table-heading table-heading-category">
                    <label>All Categories</label>
                </div>
                <div class="table-heading table-heading-buttons">
                    <button type="button">Add New Item</button>
                    <button type="button">Adjust All</button>
                </div>
            <table>
              <t<th>Product</th>
                <th>On Hand</th>
                <th>Goal Stock</th>
                <th>Incoming</th>
              </tr>
              <?php
                          $i = 0;
                          foreach($ProductResults as $ProductRow) {
                          $i++;
                      ?>
                              <tr>
                                  <td class="text-left">
                                      <a href="#"><?php echo $ProductRow['NAME'] ?></a>
                                  </td>
                                  <td class="text-right">
                                     <a href="#"><?php echo $ProductRow['QTYONHAND'] ?></a>
                                </td>
                                <td class="text-right">
                                    <a href="#"><?php echo $ProductRow['GOALSTOCK'] ?></a>
                                </td>
                                <td>
                                    <div class="incoming-textbox-div"><input class="incoming-textbox" type="number" id="incomingAmt" name="incomingAmt"></div><div class="adjust-button-div"><button type="button">Adjust Stock</button></div>
                                </td>
                              </tr>

                      <?php } ?>
            </table>
          </div>
        </div>
    </section>
</body>
</html>
<?php