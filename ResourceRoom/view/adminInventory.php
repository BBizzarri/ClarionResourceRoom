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
                <label for="category">Category:</label><br>
                <select class="sidebar-dropdown" name="category" id="category" onchange="categoryLookup()">
                    <option value="0">Filter By Category</option>
                    <?php foreach ($results as $row){ ?>
                            <option value="<?php echo $row['CATEGORYID']?>"><?php echo $row['DESCRIPTION'] ?></option>
                    <?php } ?>



                  <!--<option value="Household Supplies">Household Supplies</option>
                  <option value="Hygiene & Personal Care Items">Hygiene & Personal Care Items</option>
                  <option value="Linens">Linens</option>
                  <option value="Breakfast Foods">Breakfast Foods</option>
                  <option value="Beverages">Beverages</option>
                  <option value="Meal Items">Meal Items</option>
                  <option value="Pasta & Rice">Pasta & Rice</option>
                  <option value="Side Dishes">Side Dishes</option>
                  <option value="Soup">Soup</option>
                  <option value="Fruit">Fruit</option>
                  <option value="Snack Items">Snack Items</option>
                  <option value="Canned Vegetables, Beans, & Meats">Canned Vegetables, Beans, & Meats</option>
                  <option value="Condiments & Seasonings">Condiments & Seasonings</option>
                  <option value="Baking">Baking</option>-->
                </select>
            </div>
            <div class="sidebar-elements">
                <div class="incoming-textbox-div">
                    <label for="qtyLessThan"> Quantity Less Than:</label><br>
                </div>
                <div class="incoming-textbox-div">
                    <input class="incoming-textbox" type="number" id="qtyLessThan" name="qtyLessThan" value="qtyLessThan">
                </div>
            </div>
            <div class="sidebar-elements">
                <input type="checkbox" id="inactiveItems" name="inactiveItems" value="inactiveItems">
                <label for="inactiveItems"> Show Inactive Items</label><br>
            </div>
            <div>
                <button class="apply-button" type="button">Apply</button>
            </div>
          </div>
          <div class="container column column-spacing">
                <div class=" clarion-white table-heading table-heading-category">
                    <label>All Categories</label>
                </div>
                <div class="table-heading table-heading-buttons">
                    <button type="button">Add New Item</button>
                    <button type="button">Adjust All</button>
                </div>
            <table>
              <tr>
                <th>Product</th>
                <th>On Hand</th>
                <th>Goal Stock</th>
                <th>Incoming</th>
              </tr>
              <tr>
                <td class="text-left">Strawberry Shampoo</td>
                <td class="text-right">1</td>
                <td class="text-right">0</td>
                <td><div class="incoming-textbox-div"><input class="incoming-textbox" type="number" id="incomingAmt" name="incomingAmt"></div><div class="adjust-button-div"><button type="button">Adjust Stock</button></div></td>
              </tr>
              <tr>
                <td class="text-left">Ocean Breeze Shampoo</td>
                <td class="text-right">4</td>
                <td class="text-right">5</td>
                <td><div class="incoming-textbox-div"><input class="incoming-textbox" type="number" id="incomingAmt" name="incomingAmt"></div><div class="adjust-button-div"><button type="button">Adjust Stock</button></div></td>
              </tr>
              <tr>
                <td class="text-left">Ocean Breeze Conditionor</td>
                <td class="text-right">5</td>
                <td class="text-right">5</td>
                <td><div class="incoming-textbox-div"><input class="incoming-textbox" type="number" id="incomingAmt" name="incomingAmt"></div><div class="adjust-button-div"><button type="button">Adjust Stock</button></div></td>
              </tr>
              <tr>
                <td class="text-left">Topical Coconut Shampoo</td>
                <td class="text-right">2</td>
                <td class="text-right">5</td>
                <td><div class="incoming-textbox-div"><input class="incoming-textbox" type="number" id="incomingAmt" name="incomingAmt"></div><div class="adjust-button-div"><button type="button">Adjust Stock</button></div></td>
              </tr>
              <tr>
                <td class="text-left">Topical Coconut Conditionor</td>
                <td class="text-right">4</td>
                <td class="text-right">5</td>
                <td><div class="incoming-textbox-div"><input class="incoming-textbox" type="number" id="incomingAmt" name="incomingAmt"></div><div class="adjust-button-div"><button type="button">Adjust Stock</button></div></td>
              </tr>
              <tr>
                <td class="text-left">Dry Shampoo Spray</td>
                <td class="text-right">6</td>
                <td class="text-right">5</td>
                <td><div class="incoming-textbox-div"><input class="incoming-textbox" type="number" id="incomingAmt" name="incomingAmt"></div><div class="adjust-button-div"><button type="button">Adjust Stock</button></div></td>
              </tr>
              <tr>
                <td class="text-left">Curling Cream</td>
                <td class="text-right">0</td>
                <td class="text-right">3</td>
                <td><div class="incoming-textbox-div"><input class="incoming-textbox" type="number" id="incomingAmt" name="incomingAmt"></div><div class="adjust-button-div"><button type="button">Adjust Stock</button></div></td>
              </tr>
              <tr>
                <td class="text-left">Olive Oil Style Gel</td>
                <td class="text-right">3</td>
                <td class="text-right">3</td>
                <td><div class="incoming-textbox-div"><input class="incoming-textbox" type="number" id="incomingAmt" name="incomingAmt"></div><div class="adjust-button-div"><button type="button">Adjust Stock</button></div></td>
              </tr>
              <tr>
                <td class="text-left">Hair Pick</td>
                <td class="text-right">3</td>
                <td class="text-right">4</td>
                <td><div class="incoming-textbox-div"><input class="incoming-textbox" type="number" id="incomingAmt" name="incomingAmt"></div><div class="adjust-button-div"><button type="button">Adjust Stock</button></div></td>
              </tr>
              <tr>
                <td class="text-left">Lift and Style Comb</td>
                <td class="text-right">4</td>
                <td class="text-right">3</td>
                <td><div class="incoming-textbox-div"><input class="incoming-textbox" type="number" id="incomingAmt" name="incomingAmt"></div><div class="adjust-button-div"><button type="button">Adjust Stock</button></div></td>
              </tr>
              <tr>
                <td class="text-left">Wide Tooth Comb</td>
                <td class="text-right">0</td>
                <td class="text-right">3</td>
                <td><div class="incoming-textbox-div"><input class="incoming-textbox" type="number" id="incomingAmt" name="incomingAmt"></div><div class="adjust-button-div"><button type="button">Adjust Stock</button></div></td>
              </tr>
              <tr>
                <td class="text-left">Regular Comb</td>
                <td class="text-right">2</td>
                <td class="text-right">3</td>
                <td><div class="incoming-textbox-div"><input class="incoming-textbox" type="number" id="incomingAmt" name="incomingAmt"></div><div class="adjust-button-div"><button type="button">Adjust Stock</button></div></td>
              </tr>
              <tr>
                <td class="text-left">Do Rag</td>
                <td class="text-right">2</td>
                <td class="text-right">3</td>
                <td><div class="incoming-textbox-div"><input class="incoming-textbox" type="number" id="incomingAmt" name="incomingAmt"></div><div class="adjust-button-div"><button type="button">Adjust Stock</button></div></td>
              </tr>
              <tr>
                <td class="text-left">Night Bonnet</td>
                <td class="text-right">3</td>
                <td class="text-right">5</td>
                <td><div class="incoming-textbox-div"><input class="incoming-textbox" type="number" id="incomingAmt" name="incomingAmt"></div><div class="adjust-button-div"><button type="button">Adjust Stock</button></div></td>
              </tr>
            </table>
          </div>
        </div>
    </section>
</body>
</html>
<script>
    function categoryLookup ()
    {
        document.location = "../Controller/inventoryCasesInclude?action=displayCategory&CATEGORYID=" +
                $('#category').val();
    }
</script>
<?php