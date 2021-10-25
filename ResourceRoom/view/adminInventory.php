<?php
    $title = " Admin Inventory Page";
    require '../view/headerInclude.php';
?>
<html>
<body>
    <section class="clarion-blue">
        <div class="row row-alignment">
          <div class="column sidebar">


          <div class="sidebar-search-div">
            <form class="sidebar-search-form form-inline my-2 my-lg-0">
                          <input class="form-control mr-sm-2" type="text" id="AdminCriteria" placeholder="Search" aria-label="Search">
                          <input class="btn my-2 my-sm-0" id="adminSearchButton"type="button" value="Search" onclick="generalSearchAdmin();"/>
            </form>
          </div>
          <form id="filterForm" action="../Controller/Controller.php?action=applyFilter&Display=<?php echo $Display?>&CATEGORYID=<?php echo $CATEGORYID?>&DESCRIPTION=<?php echo $DESCRIPTION?>" method="post" enctype="multipart/form-data">
              <div class="sidebar-elements">
                  <div class="incoming-textbox-div">
                      <h3 class="sidebar-heading">Filter Options</h3>
                      <label for="qtyLessThan">Quantity Less Than:</label>
                  </div>
                  <div class="incoming-textbox-div">
                      <input class="incoming-textbox" type="number" id="QtyLessThan" name="QtyLessThan" value="<?php echo $_POST['QtyLessThan']?>"/>
                  </div>
              </div>
              <div class="sidebar-elements">
                  <label for="stockedItems">Show Stocked Items Only</label>
                  <input type="checkbox" id="stockedItems" name="stockedItems" <?php if(isset($_POST['stockedItems'])) echo "checked='checked'"; ?> />
              </div>
              <div class="sidebar-elements">
                  <label for="inactiveItems">Include Inactive Items</label>
                  <input type="checkbox" id="inactiveItems" name="inactiveItems" <?php if(isset($_POST['inactiveItems'])) echo "checked='checked'"; ?> />
              </div>
              <div class="filter-buttons">
                  <input class="filter-button" type="button" onclick="clearFilters()" value="Clear"/>
                  <input class="filter-button" type="submit" value="Apply"/>
              </div>
              <li class="nav-item">
                  <a class="category nav-link" href="#">Shopping List</a>
              </li>
          </form>
            <hr class="sidebar-seperator">
            <div class="sidebar-elements">
                  <h3 class="sidebar-heading">Categories</h3>
                  <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="category nav-link" href="../controller/controller.php?action=displaySelectedCategory&Display=<?php echo 'All' ?>">All</a>
                    </li>
                    <?php foreach ($CategoryArray as $category) {
                    ?>
                        <li class="nav-item">
                            <a class="category nav-link " href="../controller/controller.php?action=displaySelectedCategory&CATEGORYID=<?php echo $category->getCategoryID()?>&DESCRIPTION=<?php echo htmlspecialchars($category->getCategoryDescription())?>&Display=<?php echo 'category' ?>"><?php echo htmlspecialchars($category->getCategoryDescription()) ?></a>
                        </li>
                    <?php
                    }
                    ?>
                  </ul>
            </div>
          </div>
          <form id="bulkAdjustForm" action="../controller/controller.php?action=processBulkStockAdjust" method="post" enctype="multipart/form-data">
              <div class="container column column-spacing">
                    <div class=" clarion-white table-heading table-heading-category">
                        <label><?php echo $CategoryHeader ?></label>
                    </div>
                    <div class="table-heading table-heading-buttons">
                        <input class="btn my-2 my-sm-0" id="addNewItemButton" type="button" data-toggle="modal" data-target="#addProductModal" value="Add New Item"/>
                            <input class="btn my-2 my-sm-0" id="adjustAllButton" type="submit" value="Adjust All"/>
                    </div>
          </form>
                <table class="clarion-white">
                <tr>
                    <th>Product</th>
                    <th>On Hand</th>
                    <th>Goal Stock</th>
                    <th>Incoming</th>
                  </tr>
                  <?php
                              $i = 0;
                              foreach($ProductArray as $product) {
                              $i++;
                              $ProductID = $product->getProductID();
                  ?>
                                  <tr>
                                      <td class="text-left">
                                          <a class="clarion-white" href="#" data-toggle="modal" data-target="#editProductModal_<?php echo $product->getProductID()?>"><?php echo htmlspecialchars($product->getProductName())?></a>
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
                                                <input class="incoming-textbox" type="number" id="incomingAmt_<?php echo $product->getProductID()?>" value="" name="incomingAmt">
                                            </div>
                                            <div class="adjust-button-div">
                                                <input type="submit" value="Adjust Stock">
                                            </div>
                                        </form>
                                    </td>
                                  </tr>

                                          <!-- Edit Product Modal -->
                                          <div class="modal fade" id="editProductModal_<?php echo $product->getProductID()?>" role="dialog">
                                            <div class="modal-dialog modal-lg">-->

                                              <!-- Modal content-->
                                              <form id="editProductForm_<?php echo htmlspecialchars($product->getProductID()) ?>" action="../controller/controller.php?action=addEditProduct&productMode=edit" method="post" enctype="multipart/form-data" >
                                                  <div class="modal-content clarion-blue clarion-white">
                                                    <div class="modal-header" style="border-bottom: 1px solid #97824A;">
                                                      <h4>Name: <input type="text" name="ProductName" value="<?php echo htmlspecialchars($product->getProductName())?>" required maxlength="50" autofocus/></h4>
                                                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    </div>
                                                    <div class="row modal-body">
                                                        <div class="column product-info-left">
                                                            <input type="hidden" id="CurrentProductID_<?php echo htmlspecialchars($product->getProductID()) ?>" name="ProductID" value="<?php echo htmlspecialchars($product->getProductID()) ?>"/>
                                                            <h4 class="product-info-spacing" for="categorySelectEdit_<?php echo htmlspecialchars($product->getProductID()) ?>">Categories:
                                                                <select id="categorySelectEdit_<?php echo htmlspecialchars($product->getProductID()) ?>" class="selectpicker" name="CategoriesEdit[]" multiple form="editProductForm_<?php echo htmlspecialchars($product->getProductID()) ?>">
                                                                    <?php foreach ($CategoryArray as $category) { ?>
                                                                        <option <?php foreach($product->getProductCategories() as $SingleCategory){
                                                                                            if($SingleCategory == $category->getCategoryDescription()){
                                                                                                echo 'selected';
                                                                                            }
                                                                                        }?> value="<?php echo htmlspecialchars($category->getCategoryID()) ?>"><?php echo htmlspecialchars($category->getCategoryDescription()) ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </h4>
                                                            <h4 class="product-info-spacing">QTY On Hand: <input type="number" name="QtyOnHand" value="<?php echo htmlspecialchars($product->getProductQTYOnHand())?>" required/></h4>
                                                            <h4 class="product-info-spacing">Max Order QTY: <input type="number" name="MaxOrderQty" value="<?php echo htmlspecialchars($product->getProductMaxOrderQty())?>"/></h4>
                                                            <h4 class="product-info-spacing">Goal Stock: <input type="number" name="GoalStock" value="<?php echo htmlspecialchars($product->getProductGoalStock())?>" required/></h4>
                                                            <h4>Description:</h4><textarea name="ProductDescription" rows="4" cols="50"><?php echo htmlspecialchars($product->getProductDescription())?></textarea>
                                                        </div>
                                                        <div class="column product-info-right">
                                                            <!--<img class="product-info-spacing" src="../Images/productSizeImage.jpg">-->
                                                            Select image to upload:
                                                            <input type="file" name="ProductImage" id="ProductImage_<?php echo htmlspecialchars($product->getProductID()) ?>">
                                                            <input type="submit" value="Upload Image" name="submit">
                                                            <!--<img src="../productImages/'.$new_Name[0].'.jpg">'-->
                                                            <img <?php if(file_exists("../productImages/{$product->getProductID()}.jpg")):?>
                                                                    src="../productImages/<?php echo($product->getProductID())?>.jpg"
                                                                 <?php else :?>
                                                                    src="https://dummyimage.com/256x256/000/fff.jpg"
                                                                 <?php endif ;?>
                                                                    class="card-img-top" alt="..." data-toggle="modal" data-target="#productModal_<?php echo $product->getProductID()?>">
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer" style="border-top: 1px solid #97824A;">
                                                      <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                                      <button type="submit" class="btn btn-default">Save</button>
                                                    </div>
                                                </form>
                                              </div>
                                            </div>
                                          </div>

                                          <!-- Add Product Modal -->
                                          <div class="modal fade" id="addProductModal" role="dialog">
                                            <div class="modal-dialog modal-lg">-->
                                                <!-- Modal content-->
                                                <form id="addProductForm" action="../controller/controller.php?action=addEditProduct&productMode=Add" method="post" enctype="multipart/form-data">
                                                    <div class="modal-content clarion-blue clarion-white">
                                                      <div class="modal-header" style="border-bottom: 1px solid #97824A;">
                                                        <h4>Name: <input type="text" name="ProductName" value="" required maxlength="50" autofocus/></h4>
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                      </div>
                                                      <div class="row modal-body">
                                                          <div class="column product-info-left">
                                                              <!--<input type="hidden" name="ProductID" value="<?php echo htmlspecialchars($product->getProductID()) ?>"/>-->
                                                              <h4 class="product-info-spacing" for="categorySelect">Categories:
                                                                  <select id="categorySelect" class="selectpicker" name="Categories[]" multiple form="addProductForm">
                                                                      <?php foreach ($CategoryArray as $category) { ?>
                                                                      <option value="<?php echo htmlspecialchars($category->getCategoryID()) ?>"><?php echo htmlspecialchars($category->getCategoryDescription()) ?></option>
                                                                      <?php } ?>
                                                                  </select>
                                                              </h4>
                                                              <h4 class="product-info-spacing">QTY On Hand: <input type="number" name="QtyOnHand" value="" required/></h4>
                                                              <h4 class="product-info-spacing">Max Order QTY: <input type="number" name="MaxOrderQty" value=""/></h4>
                                                              <h4 class="product-info-spacing">Goal Stock: <input type="number" name="GoalStock" value="" required/></h4>
                                                              <h4>Description:</h4><textarea id="description" name="ProductDescription" rows="4" cols="50"></textarea>
                                                          </div>
                                                          <div class="column product-info-right">
                                                            <!--<form action="uploads.php" method="post" enctype="multipart/form-data">-->
                                                                Select image to upload:
                                                                <input type="file" name="ProductImage" id="ProductImage">
                                                                <!--<img src="../productImages/'.$new_Name[0].'.jpg">'-->
                                                                 <!--<img class="product-info-spacing" src="../Images/productSizeImage.jpg">-->
                                                            <!--</form>-->
                                                          </div>
                                                      </div>
                                                          <div class="modal-footer" style="border-top: 1px solid #97824A;">
                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn btn-default">Save</button>
                                                          </div>
                                                    </div>
                                                </form>
                                            </div>
                                          </div>
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