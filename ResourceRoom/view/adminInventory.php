<?php
    $title = " Admin Inventory Page";
    require '../view/headerInclude.php';
    require_once '../lib/Mobile_Detect.php';
    $detect = new Mobile_Detect;
?>
    <section class="clarion-blue">
        <div>
            <div id="hiddenMenu" class="overlay" >
                <!-- Button to close the overlay navigation -->
                <!-- Overlay content -->
                <div class="overlay-content" style="background-color: white">
                    <form id="filterFormHidden" action="../controller/controller.php?action=adminInventory" method="post" enctype="multipart/form-data">
                        <div class = "row">
                            <div class = "container-fluid">
                                <div class = "row">
                                    <div class = "col-6">
                                        <div class="col-12">
                                            <h3 class="sidebar-heading">Filter Options</h3>
                                        </div>
                                        <div class="filterOption col-12">
                                            <input class="form-control mr-sm-2" type="text" id="adminSearchCriteria1" name="adminSearchCriteria" value="<?php if($_SESSION['SearchTerm']){echo $_SESSION['SearchTerm'];}?>" placeholder="<?php if(!isset($_POST['adminSearchCriteria'])){echo 'Search';}?>">
                                            <!--<input class="btn my-2 my-sm-0" id="adminSearchButton"type="button" value="Search" onclick="generalSearchAdmin();"/>-->
                                        </div>
                                        <div class="filterOption col-12">
                                            <label for="qtyLessThan" title="Only show items with a quantity less than">Quantity Less Than:</label>
                                            <input class="incoming-textbox" type="number" min="0" id="QtyLessThan1" name="QtyLessThan" value="<?php echo $_SESSION['QtyLessThan']?>"/>
                                        </div>
                                        <div class="filterOption col-12">
                                            <label for="stockedItems" title="Only show items with a goal stock that is greater than 0">Show Stocked Items Only</label>
                                            <input type="hidden" value = '0' name = 'stockedItems'>
                                            <input type="checkbox" id="stockedItems1" name="stockedItems" <?php if($_SESSION['StockedItems']) echo "checked='checked'"; ?> />
                                        </div>
                                        <div class="filterOption col-12">
                                            <label for="inactiveItems" title="Only show items with a qty on hand equal to 0 and a goal stock equal to 0">Include Inactive Items</label>
                                            <input type="hidden" value = '0' name = 'inactiveItems'>
                                            <input type="checkbox" id="inactiveItems1" name="inactiveItems" <?php if($_SESSION['InactiveItems']) echo "checked='checked'"; ?> />
                                        </div>
                                        <div class="filterOption col-12">
                                            <label for="shoppingList" title="Only show items that need to be restocked (qty on hand is less than the goal stock)">Shopping List Only</label>
                                            <input type="hidden" value = '0' name = 'shoppingList'>
                                            <input type="checkbox" id="shoppingList1" name="shoppingList" <?php if($_SESSION['ShoppingList']) echo "checked='checked'"; ?> />
                                        </div>
                                        <div class="filterOption col-12">
                                            <a href="../controller/controller.php?action=adminInventory&ClearFilters=true" class="btn btn-secondary filter-button" role="button">Clear</a>
                                            <input class="btn btn-secondary filter-button" type="submit" value="Apply"/>
                                            <button type="button" class="btn btn-danger d-lg-none" onclick="closeNav()">Close</button>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                    <h3 class="sidebar-heading">Categories</h3>
                                    <div class="col-12">
                                        <select multiple class="category-list col-12" size="<?php echo sizeof($CategoryArray) + 1; ?>" id="categorySelect1" name="CategoryList[]" form="filterFormHidden">
                                            <option class="category nav-link col-12" style="white-space: normal" value="0" <?php if(empty($info[1])){echo 'selected';}?>>All</option>
                                            <?php foreach ($CategoryArray as $category) {
                                                ?>
                                                <option class="category nav-link col-12" style="white-space: normal" value="<?php echo $category->getCategoryID()?>" <?php if(in_array($category->getCategoryID(), $info[1])){echo 'selected';}?>> <?php echo htmlspecialchars($category->getCategoryDescription())?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </div>
                    </form>
                </div>
            </div>
            <script>
                /* Open when someone clicks on the span element */
                function openNav() {
                    document.getElementById("hiddenMenu").style.width = "100%";
                }

                /* Close when someone clicks on the "x" symbol inside the overlay */
                function closeNav() {
                    document.getElementById("hiddenMenu").style.width = "0%";
                }
            </script>
        </div>
        <div class="container-fluid">
            <div class ="row">
                <div class="col-lg-3 col-xl-3 d-none d-sm-none d-md-none d-lg-block sidebar">
                    <form id="filterForm" class="form-horizontal" action="../controller/controller.php?action=adminInventory" method="post" enctype="multipart/form-data">
                        <div class ='form-group'>
                            <h3 class="sidebar-heading">Filter Options</h3>
                        </div>
                        <div class ='form-group filterOption'>
                            <input class="form-control mr-sm-2" type="text" id="adminSearchCriteria" name="adminSearchCriteria" value="<?php if($_SESSION['SearchTerm']){echo $_SESSION['SearchTerm'];}?>" placeholder="<?php if(!isset($_POST['adminSearchCriteria'])){echo 'Search';}?>">
                            <!--<input class="btn my-2 my-sm-0" id="adminSearchButton"type="button" value="Search" onclick="generalSearchAdmin();"/>-->
                        </div>
                        <div class ='form-group filterOption'>
                            <label for="qtyLessThan" title="Only show items with a quantity less than">Quantity Less Than:</label>
                            <input class="incoming-textbox" type="number" min="0" id="QtyLessThan" name="QtyLessThan" value="<?php echo $_SESSION['QtyLessThan']?>"/>
                        </div>
                        <div class ='form-group filterOption'>
                            <label for="stockedItems" title="Only show items with a goal stock that is greater than 0">Show Stocked Items Only</label>
                            <input type="hidden" value = '0' name = 'stockedItems'>
                            <input type="checkbox" id="stockedItems" name="stockedItems" <?php if($_SESSION['StockedItems']) echo "checked='checked'"; ?> />
                        </div>
                        <div class ='form-group filterOption'>
                            <label for="inactiveItems" title="Only show items with a qty on hand equal to 0 and a goal stock equal to 0">Include Inactive Items</label>
                            <input type="hidden" value = '0' name = 'inactiveItems'>
                            <input type="checkbox" id="inactiveItems" name="inactiveItems" <?php if($_SESSION['InactiveItems']) echo "checked='checked'"; ?> />
                        </div>
                        <div class ='form-group filterOption'>
                            <label for="shoppingList" title="Only show items that need to be restocked (qty on hand is less than the goal stock)">Shopping List Only</label>
                            <input type="hidden" value = '0' name = 'shoppingList'>
                            <input type="checkbox" id="shoppingList" name="shoppingList" <?php if($_SESSION['ShoppingList']) echo "checked='checked'"; ?> />
                        </div>
                        <div class ='form-group filterOption'>
                            <a href="../controller/controller.php?action=adminInventory&ClearFilters=true" class="btn btn-secondary filter-button" role="button">Clear</a>
                            <input class="btn btn-secondary filter-button" type="submit" value="Apply"/>
                        </div>
                            <div class="col-sm-6 col-md-6 col-lg-12 col-xl-12">
                            <hr class="sidebar-seperator">
                            <h3 class="sidebar-heading">Categories</h3>
                            <div class="col-12">
                                <select multiple class="category-list col-12" size="<?php echo sizeof($CategoryArray) + 1; ?>" id="categorySelect" name="CategoryList[]" form="filterForm">
                                    <option class="category nav-link col-12" style="white-space: normal" value="0" <?php if(empty($info[1])){echo 'selected';}?>>All</option>
                                    <?php foreach ($CategoryArray as $category) {
                                        ?>
                                        <option class="category nav-link col-12" style="white-space: normal" value="<?php echo $category->getCategoryID()?>" <?php if(in_array($category->getCategoryID(), $info[1])){echo 'selected';}?>> <?php echo htmlspecialchars($category->getCategoryDescription())?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                   </form>
                </div>
                <div class="col-sm-12 col-md-12 col-lg-9 col-xl-9">
                    <div class ="row">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-heading">
                                        <h3 class="clarion-white"><?php echo $CategoryHeader ?></h3>
                                    </div>
                                    <?php if(!$detect->isMobile()){ ?>
                                        <div class="table-heading table-heading-buttons">
                                            <input class="btn my-2 my-sm-0" id="addNewItemButton" type="button" data-toggle="modal" data-target="#addProductModal" value="Add New Item"/>
                                        </div>
                                    <?php } ?>
                                </div>
                                <div class="col-12">
                                    <button type="button" class="btn btn-info d-lg-none" onclick="openNav()">Show Filters</button>
                                    <form id="adjustBulkForm" action="../controller/controller.php?action=processStockAdjust&Type=bulk" method="post" enctype="multipart/form-data">
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
                                        <div class = "row">
                                            <div class="table-responsive">
                                                <table class="table table-striped inventoryTable" id="inventoryTable">
                                                    <thead>
                                                    <tr>
                                                        <th>Product</th>
                                                        <th>Available <?php if($_SESSION['QtyLessThan'] != null){ echo '<' . ' ' . $_SESSION['QtyLessThan'];}?></th>
                                                        <th>On Hand</th>
                                                        <th>Goal Stock</th>
                                                        <th>Incoming <input class="btn my-2 my-sm-0" type="button" value="Adjust All" data-toggle="modal" data-target="#adjustBulkConfirmModal"/> </th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php
                                                    $i = 0;
                                                    foreach($ProductArray as $product)
                                                    {
                                                        ?>
                                                        <tr>
                                                            <td class="text-left">
                                                                <a class="clarion-white" href="#" data-toggle="modal" data-target="#editProductModal_<?php echo $product->getProductID()?>"><?php echo htmlspecialchars($product->getProductName())?></a>
                                                            </td>
                                                            <td class="text-right"><?php echo $product->getProductQTYAvailable(); ?></td>
                                                            <td class="text-right"><?php echo $product->getProductQtyOnHand() ?></td>
                                                            <td class="text-right"><?php echo $product->getProductGoalStock()?></td>
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
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>
            $(document).ready(function()
            {
                $("#inventoryTable").DataTable(
                    {
                        searching: false,
                        "pageLength" : 100,
                        dom:'Bfrtip',
                        buttons: [
                            {
                                extend: 'csvHtml5',
                                text: 'Excel',
                                header: true
                            },
                            {
                                extend: 'print',
                            }
                        ]
                    });
            } );
        </script>

            <?php
              foreach($ProductArray as $product) {
            ?>
              <!-- Edit Product Modal -->
            <div class="modal fade" id="editProductModal_<?php echo $product->getProductID()?>" role="dialog">
                <div class="modal-dialog modal-lg">
                    <!-- Modal content-->
                    <form id="editProductForm_<?php echo htmlspecialchars($product->getProductID()) ?>" action="../controller/controller.php?action=addEditProduct&productMode=edit" method="post" enctype="multipart/form-data" >
                        <div class="modal-content clarion-blue clarion-white">
                            <div class="modal-header" style="border-bottom: 1px solid #97824A;">
                                <h4>Name: <input type="text" name="ProductName" value="<?php echo htmlspecialchars($product->getProductName())?>" required maxlength="50" autofocus/></h4>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <div class="add-edit-container modal-body container">
                                <div class="row">
                                    <div class="col item">
                                        <input type="hidden" id="CurrentProductID_<?php echo htmlspecialchars($product->getProductID()) ?>" name="ProductID" value="<?php echo htmlspecialchars($product->getProductID()) ?>"/>
                                        <h4 class="product-info-spacing" for="categorySelectEdit_<?php echo htmlspecialchars($product->getProductID()) ?>">Categories:
                                            <select id="categorySelectEdit_<?php echo htmlspecialchars($product->getProductID()) ?>" class="selectpicker" name="CategoriesEdit[]" multiple form="editProductForm_<?php echo htmlspecialchars($product->getProductID()) ?>" required>
                                                <?php foreach ($CategoryArray as $category) { ?>
                                                    <option <?php foreach($product->getProductCategories() as $SingleCategory){
                                                        if($SingleCategory->getCategoryID() == $category->getCategoryID()){
                                                            echo 'selected';
                                                        }
                                                    }?> value="<?php echo htmlspecialchars($category->getCategoryID()) ?>"><?php echo htmlspecialchars($category->getCategoryDescription()) ?></option>
                                                <?php } ?>
                                            </select>
                                        </h4>
                                        <h4 class="product-info-spacing">QTY On Hand: <input class="col-12 type="number" min="0" name="QtyOnHand" value="<?php echo htmlspecialchars($product->getProductQTYOnHand())?>" required/></h4>
                                        <h4 class="product-info-spacing">Max Order QTY: <input class="col-12 type="number" min="0" name="MaxOrderQty" value="<?php echo htmlspecialchars($product->getProductMaxOrderQty())?>"/></h4>
                                        <h4 class="product-info-spacing">Goal Stock: <input class="col-12 type="number" min="0" name="GoalStock" value="<?php echo htmlspecialchars($product->getProductGoalStock())?>" required/></h4>
                                        <h4>Description:</h4>
                                        <textarea name="ProductDescription" rows="4" class="col-12"><?php echo htmlspecialchars($product->getProductDescription())?></textarea>
                                    </div>
                                    <div class="col item">
                                        <div>
                                            Select image to upload:
                                            <input type="file" name="ProductImage" id="ProductImage_<?php echo htmlspecialchars($product->getProductID()) ?>">
                                        </div>
                                        <div>
                                            <img <?php if(file_exists("../productImages/{$product->getProductID()}.jpg")):?>
                                                src="../productImages/<?php echo($product->getProductID())?>.jpg"
                                            <?php else :?>
                                                src="../productImages/ImageNotAvailable.jpg"
                                            <?php endif ;?>
                                                    alt="..." data-toggle="modal" data-target="#productModal_<?php echo $product->getProductID()?>">
                                        </div>
                                    </div>
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

        <!-- Add Product Modal -->
        <div class="modal fade" id="addProductModal" role="dialog">
            <div class="modal-dialog modal-lg">
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
                                    <select id="categorySelect" class="selectpicker" name="Categories[]" multiple form="addProductForm" required>
                                        <?php foreach ($CategoryArray as $category) { ?>
                                            <option value="<?php echo htmlspecialchars($category->getCategoryID()) ?>"><?php echo htmlspecialchars($category->getCategoryDescription()) ?></option>
                                        <?php } ?>
                                    </select>
                                </h4>
                                <h4 class="product-info-spacing">QTY On Hand: <input type="number" min="0" name="QtyOnHand" value="" required/></h4>
                                <h4 class="product-info-spacing">Max Order QTY: <input type="number" min="0" name="MaxOrderQty" value=""/></h4>
                                <h4 class="product-info-spacing">Goal Stock: <input type="number" min="0" name="GoalStock" value="" required/></h4>
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