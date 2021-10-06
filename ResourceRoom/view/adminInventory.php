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
            <hr class="sidebar-seperator">
            <div class="sidebar-elements">
                  <h3 class="sidebar-heading">Categories</h3>
                  <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="category nav-link" href="../controller/controller.php?action=displaySelectedCategory&Display=<?php echo 'All' ?>">All</a>
                    </li>
                    <li class="nav-item">
                        <a class="category nav-link" href="#">Shopping List</a>
                    </li>
                    <?php foreach ($CategoryResults as $CategoryRow) {
                    ?>
                        <li class="nav-item">
                            <a class="category nav-link " href="../controller/controller.php?action=displaySelectedCategory&CATEGORYID=<?php echo $CategoryRow['CATEGORYID']?>&DESCRIPTION=<?php echo $CategoryRow['DESCRIPTION']?>&Display=<?php echo 'category' ?>"><?php echo htmlspecialchars($CategoryRow['DESCRIPTION']) ?></a>
                        </li>
                    <?php
                    }
                    ?>
                  </ul>
            </div>
          </div>
          <div class="container column column-spacing">
                <div class=" clarion-white table-heading table-heading-category">
                    <label><?php echo $CategoryHeader ?></label>
                </div>
                <div class="table-heading table-heading-buttons">
                    <button type="button">Add New Item</button>
                    <button type="button">Adjust All</button>
                </div>
            <table class="clarion-white">
                <th>Product</th>
                <th>On Hand</th>
                <th>Goal Stock</th>
                <th>Incoming</th>
              </tr>
              <?php
                          $i = 0;
                          foreach($ProductArray as $product) {
                          $i++;
                      ?>
                              <tr>
                                  <td class="text-left">
                                      <a class="clarion-white" id="product" href="#"><?php echo htmlspecialchars($product->getProductName())?></a>
                                  </td>
                                  <td class="text-right">
                                     <?php echo $product->getProductQTYOnHand() ?>
                                </td>
                                <td class="text-right">
                                    <?php echo $product->getProductGoalStock() ?>
                                </td>
                                <td>
                                    <form action="../controller/controller.php?action=processSingleStockAdjust&ProductID=<?php echo $product->getProductID()?>&QTYOnHand=<?php echo $product->getProductQTYOnHand()?>" method="post" enctype="multipart/form-data">
                                        <div class="incoming-textbox-div">
                                            <input class="incoming-textbox" type="number" id="incomingAmt" name="incomingAmt">
                                        </div>
                                        <div class="adjust-button-div">
                                            <input type="submit" value="Adjust Stock">
                                        </div>
                                    </form>
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