function generalSearch(){
    var criteria = '';
    criteria = $(`#Criteria`).val();
    document.location="../controller/controller.php?action=shopperHome&ListType=GeneralSearch&Criteria=" + encodeURIComponent(criteria);
}

function generalSearchAdmin(){
    var criteria = '';
    criteria = $(`#Criteria`).val();
    document.location="../controller/controller.php?action=adminInventory&ListType=GeneralSearch&Criteria=" + encodeURIComponent(criteria);
}

function adjustAll(){
    var textBoxes = document.querySelectorAll('[id^=number]');

//    var ProductRow = "<?php echo $ProductRow['PRODUCTID']; ?>";
//    document.location="../Controller/Controller.php?action=processBulkStockAdjust&PRODUCTID=" + ProductRow;
}
