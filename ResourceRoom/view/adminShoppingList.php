<?php


?>

<form id="filterForm" action="../controller/controller.php?action=applyFilter&Display=<?php echo $Display?>&CATEGORYID=<?php echo $CategoryID?>&DESCRIPTION=<?php echo $DESCRIPTION?>" method="post" enctype="multipart/form-data">
                <div class="sidebar-elements">
                    <div class="incoming-textbox-div">
                        <h3 class="sidebar-heading">Filter Options</h3>
                        <label for="qtyLessThan">Quantity Less Than:</label>
                    </div>
                    <div class="incoming-textbox-div">
                        <input class="incoming-textbox" type="number" id="QtyLessThan" name="QtyLessThan" value="<?php echo $_POST['QtyLessThan']?>"/>
                    </div>
                </div>
                <div class="sidebar-elements">
                    <label for="stockedItems">Show Stocked Items Only</label>
                    <input type="checkbox" id="stockedItems" name="stockedItems" <?php if(isset($_POST['stockedItems'])) echo "checked='checked'"; ?> />
                </div>
                <div class="sidebar-elements">
                    <label for="inactiveItems">Include Inactive Items</label>
                    <input type="checkbox" id="inactiveItems" name="inactiveItems" <?php if(isset($_POST['inactiveItems'])) echo "checked='checked'"; ?> />
                </div>
                <div class="filter-buttons">
                    <input class="filter-button" type="button" onclick="clearFilters()" value="Clear"/>
                    <input class="filter-button" type="submit" value="Apply"/>
                </div>
                <li class="nav-item">
                    <a class="category nav-link" href="#">Shopping List</a>
                </li>
            </form>