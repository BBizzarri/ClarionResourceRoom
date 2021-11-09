<?php
    $title = "Account Settings";
    require '../view/headerInclude.php';
?>
<html>
<body>
    <section class="clarion-blue">.
         <div class="container-fluid">
                    <div class ="row">
                        <div class="col-12">
                            <div class="container-fluid">
                                  <div class="card account-settings-card">
                                    <div class="card-body clarion-blue">
                                      <h4 id="accountSettings" class="card-title clarion-white">My Account</h4>
                                    </div>
                                    <div class="account-settings-card-body account-settings-card">
                                      <label class="account-settings-label">Name:</label>
                                      <label class="account-settings-label">Brady Bizzarri</label>
                                    </div>
                                    <div class="account-settings-card-body">
                                      <label class="account-settings-label">Phone Number:</label>
                                      <label class="account-settings-label">814-553-4715</label>
                                    </div>
                                    <div class="account-settings-card-body margin-bottom">
                                      <label class="account-settings-label">Email:</label>
                                      <label class="account-settings-label">b.m.bizzarri@eagle.clarion.edu</label>
                                    </div>
                                  </div>
                                <?php if(userIsAuthorized("addEditCategory")) { ?>
                                    <div class="card account-settings-card">
                                        <div class="card-body clarion-blue">
                                            <label id="categorySettings" class="account-settings-heading clarion-white">Category Settings</label>
                                        </div>
                                        <div class="account-settings-card-body account-settings-card margin-bottom">
                                            <ul class="a">
                                                <?php foreach ($CategoryArray as $category) { ?>
                                                    <li><a class="color-black" id="<?php echo htmlspecialchars($category->getCategoryID()) ?>" href="#" data-toggle="modal" data-target="#edit_categoryModal_<?php echo htmlspecialchars($category->getCategoryID()) ?>" value="<?php echo htmlspecialchars($category->getCategoryID()) ?>"><?php echo htmlspecialchars($category->getCategoryDescription()) ?></a></li>
                                                <?php } ?>
                                            </ul>
                                            <input type="button" value="Add Category" data-toggle="modal" data-target="#add_categoryModal">
                                        </div>
                                    </div>
                                    <form id="updateEmailAnnouncementSettings" action="../controller/controller.php?action=updateEmailAnnouncementSettings" method="post" enctype="multipart/form-data" >
                                        <div class="card account-settings-card">
                                            <div class="card-body clarion-blue">
                                                <label id="emailSettings" class="account-settings-heading clarion-white">Placed Order Email Settings</label>
                                            </div>
                                            <div class="account-settings-card account-settings-card-body">
                                                <label for="ReceiversPlaced">CC:</label>
                                                <input class="vertical-align" id="ReceiversPlaced"  type="text" name="ReceiversPlaced" value="<?php echo $SettingsInfo['EmailOrderReceived'];?>"/>
                                            </div>
                                            <div class="account-settings-card account-settings-card-body">
                                                <label for="ReceivedSubject">Subject</label>
                                                <input class="vertical-align" id="ReceivedSubject" name="ReceivedSubject" value="<?php echo $SettingsInfo['OrderReceivedSubj'];?>"/>
                                            </div>
                                            <div class="account-settings-card account-settings-card-body margin-bottom">
                                                <label for="EmailTextPlaced">Message Body:</label>
                                                <textarea class="vertical-align" id="EmailTextPlaced" name="EmailTextPlaced" rows="4" cols="50"><?php echo $SettingsInfo['OrderReceivedText'];?></textarea>
                                            </div>
                                        </div>
                                        <div class="card account-settings-card">
                                            <div class="card-body clarion-blue">
                                                <label id="emailSettings" class="account-settings-heading clarion-white">Filled Order Email Settings</label>
                                            </div>
                                            <div class="account-settings-card account-settings-card-body">
                                                <label for="ReceiversFilled">CC:</label>
                                                <input class="vertical-align" id="ReceiversFilled" name="ReceiversFilled" value="<?php echo $SettingsInfo['EmailOrderFilled'];?>">
                                            </div>
                                            <div class="account-settings-card account-settings-card-body">
                                                <label for="EmailTextPlaced">Subject:</label>
                                                <input class="vertical-align" id="FilledSubject" name="FilledSubject" value="<?php echo $SettingsInfo['OrderFilledSubj'];?>">
                                            </div>
                                            <div class="account-settings-card account-settings-card-body margin-bottom">
                                                <label for="EmailTextFilled">Message Body:</label>
                                                <textarea class="vertical-align" id="EmailTextFilled" name="EmailTextFilled" rows="4" cols="50"><?php echo $SettingsInfo['OrderFilledText'];?></textarea>
                                            </div>
                                        </div>
                                        <div class="card account-settings-card margin-bottom">
                                            <div class="card-body clarion-blue">
                                                <label id="announcementSettings" class="account-settings-heading clarion-white">Announcement Settings</label>
                                            </div>
                                            <div class="account-settings-card account-settings-card-body">
                                                <label for="Announcement">Announcement that will appear in footer:</label>
                                                <textarea class="vertical-align" id="Announcement" name="Announcement" rows="4" cols="50"><?php echo $SettingsInfo['FooterText'];?></textarea>
                                            </div>
                                              <br><br>
                                              <!--<input type="submit" value="Submit">-->
                                        </div>
                                        <button type="submit" class="btn btn-default">Save Settings</button>
                                    </form>
                                <?php } ?>
                            </div>
                        </div>
         </div>
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