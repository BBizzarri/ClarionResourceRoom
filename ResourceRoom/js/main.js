function generalSearch(){
    var criteria = '';
    criteria = $(`#Criteria`).val();
    document.location="../controller/controller.php?action=shopperHome&ListType=GeneralSearch&Criteria=" + encodeURIComponent(criteria);
}

function generalSearchAdmin(){
    var criteria = '';
    criteria = $(`#AdminCriteria`).val();
    document.location="../controller/controller.php?action=adminInventory&ListType=GeneralSearch&Criteria=" + encodeURIComponent(criteria);
}

function clearFilters(){
     document.getElementById("QtyLessThan").value = "";
     let inactiveItemsCheckbox = document.getElementById('inactiveItems');
     inactiveItemsCheckbox.checked = false;
     let stockedItemsCheckbox = document.getElementById('stockedItems');
     stockedItemsCheckbox.checked = false;
     document.location="../controller/controller.php?action=adminInventory&Display=All";
}

$('.my-select').selectpicker();

function getCategories($ProductID) {
        //var ProductID = $(`#CurrentProductID`).val();
        alert($ProductID);
        document.location="../controller/controller.php?action=getProductCategories&ProductID=" + encodeURIComponent(ProductID);
}

function adjustSingleStock(ProductID) {
        let IncomingAmt = document.getElementById('incomingAmt_' + ProductID).value
        document.location="../controller/controller.php?action=processSingleStockAdjust&Type=single&IncomingAmt=" + encodeURIComponent(IncomingAmt) + "&ProductID=" + encodeURIComponent(ProductID);
}


