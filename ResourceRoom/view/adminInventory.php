<?php
    $title = " Admin Inventory Page";
    require '../view/headerInclude.php';
?>
<html>
<body>
    <section class="clarion-blue">
        <div class="row row-alignment">
          <div class="column sidebar">

           <form id="filterForm" action="../controller/controller.php?action=adminInventory" method="post" enctype="multipart/form-data">
                <div class="sidebar-elements">
                      <div class="sidebar-search-div">
                                      <input class="form-control mr-sm-2" type="text" id="adminSearchCriteria" name="adminSearchCriteria" value="<?php if(isset($_POST['adminSearchCriteria'])){echo $_POST['adminSearchCriteria'];}?>" placeholder="<?php if(!isset($_POST['adminSearchCriteria'])){echo 'Search';}?>">
                                      <!--<input class="btn my-2 my-sm-0" id="adminSearchButton"type="button" value="Search" onclick="generalSearchAdmin();"/>-->
                      </div>
                    <div class="incoming-textbox-div">
                        <h3 class="sidebar-heading">Filter Options</h3>
                        <label for="qtyLessThan" title="Only show items with a quantity less than">Quantity Less Than:</label>
                    </div>
                    <div class="incoming-textbox-div">
                        <input class="incoming-textbox" type="number" min="1" id="QtyLessThan" name="QtyLessThan" value="<?php echo $_POST['QtyLessThan']?>"/>
                    </div>
                </div>
                <div class="sidebar-elements">
                    <label for="stockedItems" title="Only show items with a goal stock that is greater than 0">Show Stocked Items Only</label>
                    <input type="checkbox" id="stockedItems" name="stockedItems" <?php if(isset($_POST['stockedItems'])) echo "checked='checked'"; ?> />
                </div>
                <div class="sidebar-elements">
                    <label for="inactiveItems" title="Only show items with a qty on hand equal to 0 and a goal stock equal to 0">Include Inactive Items</label>
                    <input type="checkbox" id="inactiveItems" name="inactiveItems" <?php if(isset($_POST['inactiveItems'])) echo "checked='checked'"; ?> />
                </div>
                <div class="sidebar-elements">
                    <label for="shoppingList" title="Only show items that need to be restocked (qty on hand is less than the goal stock)">Shopping List Only</label>
                    <input type="checkbox" id="shoppingList" name="shoppingList" <?php if(isset($_POST['shoppingList'])) echo "checked='checked'"; ?> />
                </div>
                <div class="filter-buttons">
                    <input class="filter-button" type="button" onclick="clearFilters()" value="Clear"/>
                    <input class="filter-button" type="submit" value="Apply"/>
                </div>

            <hr class="sidebar-seperator">
            <div class="sidebar-elements">
                  <h3 class="sidebar-heading">Categories</h3>
                  <div>
                    <a class="category nav-link" href="../controller/controller.php?action=adminInventory">All</a>
                    <select multiple class="category-list" size="<?php echo sizeof($CategoryArray) + 1; ?>" id="categorySelect" name="CategoryList[]" form="filterForm">
                    <?php foreach ($CategoryArray as $category) {
                    ?>
                            <option class="category nav-link" value="<?php echo $category->getCategoryID()?>" <?php if(in_array($category->getCategoryID(), $info[1])){echo 'selected';}?>> <?php echo htmlspecialchars($category->getCategoryDescription())?></option>
                    <?php
                    }
                    ?>
                    </select>
                  </div>
            </div>
          </div>
          </form>
              <div class="container column column-spacing">
                    <div class=" clarion-white table-heading table-heading-category">
                        <label><?php echo $CategoryHeader ?></label>
                    </div>
                    <form id="adjustBulkForm" action="../controller/controller.php?action=processStockAdjust&Type=bulk" method="post" enctype="multipart/form-data">
                        <div class="table-heading table-heading-buttons">
                            <input class="btn my-2 my-sm-0" id="addNewItemButton" type="button" data-toggle="modal" data-target="#addProductModal" value="Add New Item"/>
                            <input class="btn my-2 my-sm-0" type="button" value="Adjust All" data-toggle="modal" data-target="#adjustBulkConfirmModal"/>
                        </div>

                          <!-- Adjust Bulk Confirm Modal -->
                                    <div class="modal fade" id="adjustBulkConfirmModal" role="dialog">
                                      <div class="modal-dialog modal-lg">-->
                                          <!-- Modal content-->
                                              <div class="modal-content clarion-blue clarion-white">
                                                <div class="modal-header" style="border-bottom: 1px solid #97824A;">
                                                  <h4>Confirm</h4>
                                                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                </div>
                                                <div class="row modal-body">
                                                    <div class="column product-info-left">
                                                      <h4>Are you sure you want to proceed with a bulk adjust?</h4>
                                                    </div>
                                                    <div class="column product-info-right">

                                                    </div>
                                                </div>
                                                    <div class="modal-footer" style="border-top: 1px solid #97824A;">
                                                      <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                                      <button type="submit" class="btn btn-default">Yes</button>
                                                    </div>
                                              </div>
                                      </div>
                                    </div>
                <table class="clarion-white">
                <tr>
                    <th>Product</th>
                    <th>On Hand <?php if(isset($_POST['QtyLessThan']) && $_POST['QtyLessThan'] != ''){ echo '<' . ' ' . $_POST['QtyLessThan'];}?></th>
                    <th>Goal Stock</th>
                    <th>Incoming</th>
                  </tr>
                  <?php
                  $i = 0;
                  foreach($ProductArray as $product) {
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
                                              <td title="To add stock enter in number you want to increase by and to subtract stock enter a '-' in front of number you want to decrease by" >
                                                <div class="incoming-textbox-div">
                                                    <input class="incoming-textbox" type="number" id="incomingAmt_<?php echo $product->getProductID()?>" value="" name="incomingAmt_<?php echo $product->getProductID()?>">
                                                </div>
                                                <div class="adjust-button-div">
                                                    <input type="button" value="Adjust Stock" onclick="adjustSingleStock(<?php echo $product->getProductID()?>);"/>
                                                </div>
                                              </td>
                                          </tr>
                          <?php } ?>
                        </table>
                    </form>
              </div>
        </div>
          <?php
              foreach($ProductArray as $product) {
          ?>
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
                                <div class="add-edit-container modal-body">
                                    <div class="item">
                                        <input type="hidden" id="CurrentProductID_<?php echo htmlspecialchars($product->getProductID()) ?>" name="ProductID" value="<?php echo htmlspecialchars($product->getProductID()) ?>"/>
                                        <h4 class="product-info-spacing" for="categorySelectEdit_<?php echo htmlspecialchars($product->getProductID()) ?>">Categories:
                                            <select id="categorySelectEdit_<?php echo htmlspecialchars($product->getProductID()) ?>" class="selectpicker" name="CategoriesEdit[]" multiple form="editProductForm_<?php echo htmlspecialchars($product->getProductID()) ?>">
                                                <?php foreach ($CategoryArray as $category) { ?>
                                                    <option <?php foreach($product->getProductCategories() as $SingleCategory){
                                                                        if($SingleCategory->getCategoryID() == $category->getCategoryID()){
                                                                            echo 'selected';
                                                                        }
                                                    }?> value="<?php echo htmlspecialchars($category->getCategoryID()) ?>"><?php echo htmlspecialchars($category->getCategoryDescription()) ?></option>
                                                <?php } ?>
                                            </select>
                                        </h4>
                                        <h4 class="product-info-spacing">QTY On Hand: <input type="number" min="0" name="QtyOnHand" value="<?php echo htmlspecialchars($product->getProductQTYOnHand())?>" required/></h4>
                                        <h4 class="product-info-spacing">Max Order QTY: <input type="number" min="0" name="MaxOrderQty" value="<?php echo htmlspecialchars($product->getProductMaxOrderQty())?>"/></h4>
                                        <h4 class="product-info-spacing">Goal Stock: <input type="number" min="0" name="GoalStock" value="<?php echo htmlspecialchars($product->getProductGoalStock())?>" required/></h4>
                                        <h4>Description:</h4><textarea name="ProductDescription" rows="4" cols="50"><?php echo htmlspecialchars($product->getProductDescription())?></textarea>
                                    </div>
                                    <div class="item">
                                        <div>
                                            Select image to upload:
                                            <input type="file" name="ProductImage" id="ProductImage_<?php echo htmlspecialchars($product->getProductID()) ?>">
                                        </div>
                                        <div>
                                            <img <?php if(file_exists("../productImages/{$product->getProductID()}.jpg")):?>
                                                    src="../productImages/<?php echo($product->getProductID())?>.jpg"
                                                 <?php else :?>
                                                    src="https://dummyimage.com/256x256/000/fff.jpg"
                                                 <?php endif ;?>
                                            alt="..." data-toggle="modal" data-target="#productModal_<?php echo $product->getProductID()?>">
                                        </div>
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
          <?php } ?>
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
                      <div class="add-edit-container modal-body">
                          <div class="item">
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
                          <div class="item">
                                Select image to upload:
                                <input type="file" name="ProductImage" id="ProductImage">
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
    </section>
</body>
</html>
<?php
    require '../view/footerInclude.php';
?>
<script>
    $(function(){
        $( "#productModal" ).on('show', function(){
            alert("Show!");
        });
    });
</script>