<?php
    $title = " Admin Inventory Categories Page";
    require '../view/headerInclude.php';
?>
<html>
<body>
    <div class="container column column-spacing">
        <div class=" clarion-white table-heading table-heading-category">
            <label>All Categories</label>
        </div>
        <div class="table-heading table-heading-buttons">
            <button type="button">Add New Item</button>
            <button type="button">Adjust All</button>
        </div>
        <table>
          <t<th>Product</th>
            <th>On Hand</th>
            <th>Goal Stock</th>
            <th>Incoming</th>
          </tr>
          <?php
              $i = 0;
              foreach($SelectedCategoryResults as $SelectedCategoryRow) {
              $i++;
          ?>
                  <tr>
                      <td class="text-left">
                          <a href="#"><?php echo $SelectedCategoryRow['NAME'] ?></a>
                      </td>
                      <td class="text-right">
                         <a href="#"><?php echo $SelectedCategoryRow['QTYONHAND'] ?></a>
                    </td>
                    <td class="text-right">
                        <a href="#"><?php echo $SelectedCategoryRow['GOALSTOCK'] ?></a>
                    </td>
                    <td>
                        <div class="incoming-textbox-div"><input class="incoming-textbox" type="number" id="incomingAmt" name="incomingAmt"></div><div class="adjust-button-div"><button type="button">Adjust Stock</button></div>
                    </td>
                  </tr>

          <?php } ?>
        </table>
    </div>
</body>
</html>
<?php