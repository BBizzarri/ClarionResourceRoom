<script src="//js.nicedit.com/nicEdit-latest.js" type="text/javascript"></script>
<script type="text/javascript">bkLib.onDomLoaded(function()
	{
	    for (let i = 1; i <= 5 ; i++) {
          new nicEditor().panelInstance('subject' + i.toString());
          new nicEditor({buttonList : ['fontFamily','fontSize','bold','italic','underline','strikeThrough','subscript','superscript','html','image']}).panelInstance('body' + i.toString());
        }


    });
</script>





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
                                      <label class="account-settings-label"><?php echo $UserInfo->getFirstName() . ' ' . $UserInfo->getLastName();?></label>
                                    </div>
                                    <div class="account-settings-card-body">
                                      <label class="account-settings-label">Username:</label>
                                      <label class="account-settings-label"><?php echo $UserInfo->getsUnderScore();?></label>
                                    </div>
                                    <div class="account-settings-card-body margin-bottom">
                                      <label class="account-settings-label">Email:</label>
                                      <label class="account-settings-label"><?php echo $UserInfo->getEmail();?></label>
                                    </div>
                                    <div class="account-settings-card-body">
                                      <p>**If this information is out of date or incorrect, please contact computing services**</p>
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
                                                <div>
                                                    <label for="ReceiversPlaced">CC:</label>
                                                </div>
                                                <input class="vertical-align settings-inputs" id="PlacedCC"  type="text" name="PlacedCC" value="<?php echo $SettingsInfo['EmailOrderReceived'];?>"/>
                                            </div>
                                            <div class="account-settings-card account-settings-card-body">
                                                <div>
                                                    <label for="PlacedBCC">BCC:</label>
                                                </div>
                                                <input class="vertical-align settings-inputs" id="PlacedBCC"  type="text" name="PlacedBCC" value="<?php echo $SettingsInfo['BCCOrderReceived'];?>"/>
                                            </div>
                                            <div class="account-settings-card account-settings-card-body">
                                                <label for="ReceivedSubject">Subject</label>
                                                <input class="vertical-align settings-inputs" id="subject1" style="width: 99%;" name="PlacedSubject" value="<?php echo $SettingsInfo['OrderReceivedSubj'];?>"/>
                                            </div>
                                            <div class="account-settings-card account-settings-card-body margin-bottom">
                                                <label for="EmailTextPlaced">Message Body:</label>
                                                <textarea class="vertical-align" id="body1" style="width: 99%;" name="PlacedText" rows="4" cols="50"><?php echo $SettingsInfo['OrderReceivedText'];?></textarea>
                                            </div>
                                        </div>
                                        <div class="card account-settings-card">
                                            <div class="card-body clarion-blue">
                                                <label id="emailSettings" class="account-settings-heading clarion-white">Filled Order Email Settings</label>
                                            </div>
                                            <div class="account-settings-card account-settings-card-body">
                                                <div>
                                                    <label for="ReceiversFilled">CC:</label>
                                                </div>
                                                <input class="vertical-align settings-inputs" id="ReceiversFilled" name="FilledCC" value="<?php echo $SettingsInfo['EmailOrderFilled'];?>">
                                            </div>
                                            <div class="account-settings-card account-settings-card-body">
                                                <div>
                                                    <label for="ReceiversBCCFilled">BCC:</label>
                                                </div>
                                                <input class="vertical-align settings-inputs" id="ReceiversBBCFilled" name="FilledBCC" value="<?php echo $SettingsInfo['BCCOrderFilled'];?>">
                                            </div>
                                            <div class="account-settings-card account-settings-card-body">
                                                <label for="EmailTextPlaced">Subject:</label>
                                                <input class="vertical-align settings-inputs" id="subject2" style="width: 99%;" name="FilledSubject" value="<?php echo $SettingsInfo['OrderFilledSubj'];?>">
                                            </div>
                                            <div class="account-settings-card account-settings-card-body margin-bottom">
                                                <label for="EmailTextFilled">Message Body:</label>
                                                <textarea class="vertical-align" id="body2" style="width: 99%;" name="FilledText" rows="4" cols="50"><?php echo $SettingsInfo['OrderFilledText'];?></textarea>
                                            </div>
                                        </div>
                                        <div class="card account-settings-card">
                                            <div class="card-body clarion-blue">
                                                <label id="emailSettings" class="account-settings-heading clarion-white">Re-Notify Email Settings</label>
                                            </div>
                                            <div class="account-settings-card account-settings-card-body">
                                                <div>
                                                    <label for="Re-Notify">CC:</label>
                                                </div>
                                                <input class="vertical-align settings-inputs" id="Re-Notify" name="ReNotifyCC" value="<?php echo $SettingsInfo['EmailOrderReminder'];?>">
                                            </div>
                                             <div class="account-settings-card account-settings-card-body">
                                                    <div>
                                                        <label for="Re-NotifyBCC">BCC:</label>
                                                    </div>
                                                    <input class="vertical-align settings-inputs" id="Re-NotifyBCC" name="ReNotifyBCC" value="<?php echo $SettingsInfo['BCCOrderReminder'];?>">
                                                </div>
                                            <div class="account-settings-card account-settings-card-body">
                                                <div>
                                                    <label for="Re-NotifySubj">Subject:</label>
                                                </div>
                                                <input class="vertical-align settings-inputs" id="subject3" style="width: 99%;" name="ReNotifySubject" value="<?php echo $SettingsInfo['OrderReminderSubj'];?>">
                                            </div>
                                            <div class="account-settings-card account-settings-card-body margin-bottom">
                                                <label for="Re-NotifyText">Message Body:</label>
                                                <textarea class="vertical-align" id="body3" style="width: 99%;" name="ReNotifyText" rows="4" cols="50"><?php echo $SettingsInfo['OrderReminderText'];?></textarea>
                                            </div>
                                        </div>
                                        <div class="card account-settings-card">
                                            <div class="card-body clarion-blue">
                                                <label id="emailSettings" class="account-settings-heading clarion-white">Cancelled Order Email Settings</label>
                                            </div>
                                            <div class="account-settings-card account-settings-card-body">
                                                <div>
                                                    <label for="CancelledOrder">CC:</label>
                                                </div>
                                                <input class="vertical-align settings-inputs" id="CancelledCC" name="CancelledCC" value="<?php echo $SettingsInfo['EmailOrderCancelled'];?>">
                                            </div>
                                            <div class="account-settings-card account-settings-card-body">
                                                <div>
                                                    <label for="CancelledBCC">BCC:</label>
                                                </div>
                                                <input class="vertical-align settings-inputs" id="CancelledBCC" name="CancelledBCC" value="<?php echo $SettingsInfo['BCCOrderCanceled'];?>">
                                            </div>
                                            <div class="account-settings-card account-settings-card-body">
                                                <label for="CancelledOrderSubj">Subject:</label>
                                                <input class="vertical-align settings-inputs" id="subject4" style="width: 99%;" name="CancelledSubject" value="<?php echo $SettingsInfo['OrderCancelledSubj'];?>">
                                            </div>
                                            <div class="account-settings-card account-settings-card-body margin-bottom">
                                                <label for="CancelledOrderText">Message Body:</label>
                                                <textarea class="vertical-align" id="body4" style="width: 99%;" name="CancelledText" rows="4" cols="50"><?php echo $SettingsInfo['OrderCancelledText'];?></textarea>
                                            </div>
                                        </div>
                                        <div class="card account-settings-card margin-bottom">
                                            <div class="card-body clarion-blue">
                                                <label id="announcementSettings" class="account-settings-heading clarion-white">Announcement Settings</label>
                                            </div>
                                            <div class="account-settings-card account-settings-card-body">
                                                <label for="AnnouncementLeft">Left Side of Footer:</label>
                                                <textarea class="vertical-align" id="body5" style="width: 99%;" name="FooterLeft" rows="4" cols="50"><?php echo $SettingsInfo['FooterTextLeft'];?></textarea>

                                                <label for="AnnouncementRight">Right Side of Footer:</label>
                                                <textarea class="vertical-align" id="body6" style="width: 99%;" name="FooterRight" rows="4" cols="50"><?php echo $SettingsInfo['FooterTextRight'];?></textarea>
                                                <!--<textarea class="vertical-align" id="body5" style="width: 99%;" name="Announcement" rows="4" cols="50"><?php echo $SettingsInfo['FooterText'];?></textarea>-->
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
          <div class="modal-dialog modal-lg">
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
                    </form>
                            <form id="deleteCategoryForm" action="../controller/controller.php?action=deleteCategory" onsubmit="return confirm('Are you sure you want to delete this category?');" method="post" enctype="multipart/form-data" >
                                <input type="hidden" id="CurrentCategory_<?php echo htmlspecialchars($category->getCategoryID()) ?>" name="CategoryID" value="<?php echo htmlspecialchars($category->getCategoryID()) ?>"/>
                                <button type="submit" class="btn btn-danger">Delete Category</button>
                            </form>
                          </div>

            </div>
          </div>
        </div>
    <?php } ?>

</body>
</html>
<?php
    require '../view/footerInclude.php';
?>