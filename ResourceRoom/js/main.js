function clearFilters(){
     document.getElementById("QtyLessThan").value = "";
     let inactiveItemsCheckbox = document.getElementById('inactiveItems');
     inactiveItemsCheckbox.checked = false;
     let stockedItemsCheckbox = document.getElementById('stockedItems');
     stockedItemsCheckbox.checked = false;
     document.location="../controller/controller.php?action=adminInventory&Display=All";
}

$('.my-select').selectpicker();

function adjustSingleStock(ProductID) {
        let IncomingAmt = document.getElementById('incomingAmt_' + ProductID).value
        document.location="../controller/controller.php?action=processStockAdjust&Type=single&IncomingAmt=" + encodeURIComponent(IncomingAmt) + "&ProductID=" + encodeURIComponent(ProductID);
}
function openEditModal($CategoryID) {
            $('#edit_categoryModal_' + $CategoryID).modal('toggle');
        }


