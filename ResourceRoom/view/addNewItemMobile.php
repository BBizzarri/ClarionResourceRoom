<?php
    $title = "Mobile Add";
    require '../view/headerInclude.php';
?>
<html>
<body>
    <section class="clarion-blue">
        <!-- Add Product Modal -->
            <form id="addProductForm" action="../controller/controller.php?action=addEditProduct&productMode=Add" method="post" enctype="multipart/form-data">
                <div class="clarion-blue clarion-white">
                  <div style="border-bottom: 1px solid #97824A;">
                    <h4>Name: <input type="text" name="ProductName" value="" required maxlength="50" autofocus/></h4>
                  </div>
                  <div class="add-edit-container">
                      <div>
                          <h4 class="product-info-spacing" for="categorySelect">Categories:
                              <select id="categorySelect" class="selectpicker" name="Categories[]" multiple form="addProductForm">
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
                      <div>
                            Select image to upload:
                            <input type="file" name="ProductImage" id="ProductImage">
                      </div>
                  </div>
                      <div style="border-top: 1px solid #97824A;">
                        <button type="submit" class="btn btn-default">Save</button>
                      </div>
                </div>
            </form>
    </section>
</body>
</html>