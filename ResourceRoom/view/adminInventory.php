<?php
    $title = " Admin Inventory Page";
    require '../view/headerInclude.php';
?>
<html>
<body>
    <section class="clarion-blue">
        <div class="row row-alignment">
          <div class="column sidebar">
            <div>
                <label for="category">Category:</label><br>
                <select name="category" id="category">
                  <option value="volvo">Volvo</option>
                  <option value="saab">Saab</option>
                  <option value="mercedes">Mercedes</option>
                  <option value="audi">Audi</option>
                </select>
            </div>
            <div>
                <input type="checkbox" id="inactiveItems" name="inactiveItems" value="inactiveItems">
                <label for="inactiveItems"> Show Inactive Items</label><br>
            </div>
          </div>
          <div class="column column-spacing">
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
<?php