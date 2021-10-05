<?php
    $title = " Admin Inventory Page";
    require '../view/headerInclude.php';
?>
<html>
<body>
    <section class="clarion-blue">
        <div class="row row-alignment">
          <div class="column sidebar">



          <form id="filterForm" action="../Controller/Controller.php?action=applyFilter&CATEGORYID=<?php echo $CategoryRow['CATEGORYID']?>&DESCRIPTION=<?php echo $CategoryRow['DESCRIPTION']?>" method="post" enctype="multipart/form-data">
              <div class="sidebar-elements">
                  <div class="incoming-textbox-div">
                      <h3 class="sidebar-heading">Filter Options</h3>
                      <label for="qtyLessThan">Quantity Less Than:</label>
                  </div>
                  <div class="incoming-textbox-div">
                      <input class="incoming-textbox" type="number" id="qtyLessThan" name="qtyLessThan" value="qtyLessThan"/>
                  </div>
              </div>
              <!--<div class="sidebar-elements">
                  <label for="inactiveItems">Include Inactive Items</label>
                  <input type="checkbox" id="inactiveItems" name="inactiveItems"/>
              </div>-->
              <div>
                  <input class="apply-button" type="submit" value="Apply"/>
              </div>
          </form>










            <hr class="sidebar-seperator">
            <div class="sidebar-elements">
                  <h3 class="sidebar-heading">Categories</h3>
                  <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="category nav-link" href="../Controller/Controller.php?action=displaySelectedCategory&Display=<?php echo 'All' ?>">All</a>
                    </li>
                    <li class="nav-item">
                        <a class="category nav-link" href="#">Shopping List</a>
                    </li>
                    <?php foreach ($CategoryResults as $CategoryRow) {
                    ?>
                        <li class="nav-item">
                            <a class="category nav-link " href="../Controller/Controller.php?action=displaySelectedCategory&CATEGORYID=<?php echo $CategoryRow['CATEGORYID']?>&DESCRIPTION=<?php echo htmlspecialchars($CategoryRow['DESCRIPTION'])?>&Display=<?php echo 'category' ?>"><?php echo htmlspecialchars($CategoryRow['DESCRIPTION']) ?></a>
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
                    <input class="btn my-2 my-sm-0" id="addNewItemButton" type="button" onclick="addNewItem();" value="Add New Item"/>
                    <input class="btn my-2 my-sm-0" id="adjustAllButton" type="button" onclick="adjustAll();" value="Adjust All"/>
                </div>
            <table class="clarion-white">
                <th>Product</th>
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
                                      <a class="clarion-white" href="#productModal" data-toggle="modal"><?php echo $ProductRow['NAME'] ?></a>
                                  </td>
                                  <td class="text-right">
                                     <?php echo htmlspecialchars($ProductRow['QTYONHAND']) ?>
                                </td>
                                <td class="text-right">
                                    <?php echo htmlspecialchars($ProductRow['GOALSTOCK']) ?>
                                </td>
                                <td>
                                    <form id="singleStockAdjust" action="../Controller/Controller.php?action=processSingleStockAdjust&ProductID=<?php echo $ProductRow['PRODUCTID']?>&QTYOnHand=<?php echo $ProductRow['QTYONHAND']?>" method="post" enctype="multipart/form-data">
                                        <div class="incoming-textbox-div">
                                            <input class="incoming-textbox" type="number" id="incomingAmt_<?php echo $ProductRow['PRODUCTID']?>" name="incomingAmt">
                                        </div>
                                        <div class="adjust-button-div">
                                            <input type="submit" value="Adjust Stock">
                                        </div>
                                    </form>
                                </td>
                              </tr>

                                      <!-- Modal -->
                                      <div class="modal fade" id="productModal" role="dialog">
                                        <div class="modal-dialog modal-lg">

                                          <!-- Modal content-->
                                          <div class="modal-content clarion-blue clarion-white">
                                            <div class="modal-header" style="border-bottom: 1px solid #97824A;">
                                              <h3><?php echo $ProductRow['NAME']?></h3>
                                              <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            </div>
                                            <div class="row modal-body">
                                                <div class="column product-info-left">
                                                    <h4 class="product-info-spacing">QTY On Hand: <?php echo htmlspecialchars($ProductRow['QTYONHAND']) ?></h4>
                                                    <h4 class="product-info-spacing">Max Order QTY: <?php echo $ProductRow['MAXORDERQTY']?></h4>
                                                    <h4 class="product-info-spacing">Goal Stock: <?php echo htmlspecialchars($ProductRow['GOALSTOCK']) ?></h4>
                                                </div>
                                                <div class="column product-info-right">
                                                    <img class="product-info-spacing" src="../Images/productSizeImage.jpg">
                                                    <p class="product-info-spacing">Description: <?php echo $ProductRow['DESCRIPTION']?></p>
                                                </div>
                                            </div>
                                            <div class="modal-footer" style="border-top: 1px solid #97824A;">
                                              <button type="button" class="btn btn-default" data-toggle="modal" data-target="#editProductModal">Edit</button>
                                              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                            </div>
                                          </div>
                                        </div>
                                      </div>

                                      <!-- Modal -->
                                      <!--<div class="modal fade" id="editProductModal" role="dialog">
                                        <div class="modal-dialog modal-lg">-->

                                          <!-- Modal content-->
                                          <!--<div class="modal-content clarion-blue clarion-white">
                                            <div class="modal-header" style="border-bottom: 1px solid #97824A;">
                                              <h3><?php echo $ProductRow['NAME']?></h3>
                                              <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            </div>
                                            <div class="row modal-body">
                                                <div class="column product-info-left">
                                                    <h4 class="product-info-spacing">QTY On Hand: <input type="number" value="<?php echo htmlspecialchars($ProductRow['QTYONHAND'])?>"/></h4>
                                                    <h4 class="product-info-spacing">Max Order QTY: <input type="number" value="<?php echo $ProductRow['MAXORDERQTY']?>"/></h4>
                                                    <h4 class="product-info-spacing">Goal Stock: <input type="number" value="<?php echo htmlspecialchars($ProductRow['GOALSTOCK'])?>"/></h4>
                                                </div>
                                                <div class="column product-info-right">
                                                    <img class="product-info-spacing" src="../Images/productSizeImage.jpg">
                                                    <p class="product-info-spacing">Description: <?php echo $ProductRow['DESCRIPTION']?></p>
                                                </div>
                                            </div>
                                            <div class="modal-footer" style="border-top: 1px solid #97824A;">
                                              <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                              <button type="button" class="btn btn-default" data-toggle="modal" data-target="#editProductInfo">Save</button>
                                            </div>
                                          </div>
                                        </div>
                                      </div>--->

                      <?php } ?>

            </table>
          </div>
        </div>
    </section>
</body>
</html>
<script>
    $(function(){
        $( "#productModal" ).on('show', function(){
            alert("Show!");
        });
    });
</script>