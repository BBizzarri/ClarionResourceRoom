<?php
    $title = "Account Settings";
    require '../view/headerInclude.php';
?>
<html>
<body>
    <section class="clarion-blue clarion-white">
        <label class="account-settings-heading">My Account</label>
        <div>
            <label class="account-settings-label">Name:</label>
            <label class="account-settings-label">Brady Bizzarri</label>
        </div>
        <div>
            <label class="account-settings-label">Phone Number:</label>
            <label class="account-settings-label">814-553-4715</label>
        </div>
        <div>
            <label class="account-settings-label">Email:</label>
            <label class="account-settings-label">b.m.bizzarri@eagle.clarion.edu</label>
        </div>
        <?php if(userIsAuthorized("addEditCategory")) { ?>
            <label class="account-settings-heading">Category Settings</label>
                <div>
                    <select name="categoriesEdit" size="<?php echo count($CategoryArray)?>">
                        <?php foreach ($CategoryArray as $category) { ?>
                            <option id="<?php echo htmlspecialchars($category->getCategoryID()) ?>" ondblclick="openEditModal(<?php echo htmlspecialchars($category->getCategoryID()) ?>)" value="<?php echo htmlspecialchars($category->getCategoryID()) ?>"><?php echo htmlspecialchars($category->getCategoryDescription()) ?></option>
                        <?php } ?>
                    </select>
                    <input type="button" value="Add Category" data-toggle="modal" data-target="#add_categoryModal">
                    <input type="button" value="Delete Category">
                </div>
            <label class="account-settings-heading">Email Settings</label>
        <?php } ?>
    </section>



    <!-- Add Category Modal -->
      <div class="modal fade" id="add_categoryModal" role="dialog">
        <div class="modal-dialog modal-lg">-->
                  <!-- Modal content-->
                  <form id="addCategoryForm" action="../controller/controller.php?action=addEditCategory&categoryMode=Add" method="post" enctype="multipart/form-data" >
                      <div class="modal-content clarion-blue clarion-white">
                        <div class="modal-header" style="border-bottom: 1px solid #97824A;">
                          <h4>Add Category</h4>
                          <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="add-edit-container modal-body">
                            <div class="item">
                                <h4 class="product-info-spacing">Category Name: <input type="text" name="CatName" value="" required/></h4>
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
    <?php
        foreach($CategoryArray as $category) {
    ?>
      <!-- Edit Category Modal -->
        <div class="modal fade" id="edit_categoryModal_<?php echo $category->getCategoryID()?>" role="dialog">
          <div class="modal-dialog modal-lg">-->
                    <!-- Modal content-->
                    <form id="editCategoryForm" action="../controller/controller.php?action=addEditCategory&categoryMode=edit" method="post" enctype="multipart/form-data" >
                        <div class="modal-content clarion-blue clarion-white">
                          <div class="modal-header" style="border-bottom: 1px solid #97824A;">
                            <h4>Add Category</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                          </div>
                          <div class="add-edit-container modal-body">
                              <div class="item">
                                <input type="hidden" id="CurrentCategory_<?php echo htmlspecialchars($category->getCategoryID()) ?>" name="CategoryID" value="<?php echo htmlspecialchars($category->getCategoryID()) ?>"/>
                                <h4 class="product-info-spacing">Category Name: <input type="text" name="CatName" value="<?php echo htmlspecialchars($category->getCategoryDescription())?>" required maxlength="50" autofocus/></h4></h4>
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

</body>
</html>
<?php
    require '../view/footerInclude.php';
?>