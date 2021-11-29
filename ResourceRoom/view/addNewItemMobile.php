<?php
    $title = "Mobile Add";
    require '../view/headerInclude.php';
?>
<html>
<body>
    <section class="clarion-blue">
        <div class="container-fluid">
            <div class ='row'>
                <div class ='col-12'>
                    <form id="addProductForm" action="../controller/controller.php?action=addEditProduct&productMode=Add" method="post" enctype="multipart/form-data">
                        <div class = 'form-group'>
                            <div style="border-bottom: 1px solid #97824A;">
                                <label class="clarion-white" for="ProductName">Name:</label>
                                <input type="text" name="ProductName" id="ProductName" value="" required maxlength="50" autofocus/>
                            </div>
                        </div>
                        <div class="form-group clarion-blue clarion-white">
                            <label class="product-info-spacing" for="categorySelect">Categories:
                                <select id="categorySelect" class="selectpicker" name="Categories[]" multiple form="addProductForm">
                                    <?php foreach ($CategoryArray as $category) { ?>
                                        <option value="<?php echo htmlspecialchars($category->getCategoryID()) ?>"><?php echo htmlspecialchars($category->getCategoryDescription()) ?></option>
                                    <?php } ?>
                                </select>
                            </label>
                        </div>
                        <div class="form-group clarion-blue clarion-white">
                            <label for="QtyOnHand"> QTY On Hand:</label>
                            <input type="number" min="0" name="QtyOnHand" id="QtyOnHand" value="" required/>
                        </div>
                        <div class="form-group clarion-blue clarion-white">
                            <label for="MaxOrderQty">Max Order QTY:</label>
                            <input type="number" min="0" name="MaxOrderQty" id="MaxOrderQty" value=""/>
                        </div>
                        <div class="form-group clarion-blue clarion-white">
                            <label for="GoalStock">Goal Stock:</label>
                            <input type="number" min="0" name="GoalStock" id='GoalStock' value="" required/>
                        </div>
                        <div class="form-group clarion-blue clarion-white">
                            <label for="description">Description:</label>
                            <textarea class="form-control" id="description" name="ProductDescription" rows="4"></textarea>
                        </div>
                        <div class="form-group clarion-blue clarion-white">
                            <label for="ProductImage">Select image to upload:</label>
                            <input type="file" name="ProductImage" id="ProductImage">
                        </div>
                        <div style="border-top: 1px solid #97824A;">
                            <button type="submit" class="btn btn-default">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</body>
</html>