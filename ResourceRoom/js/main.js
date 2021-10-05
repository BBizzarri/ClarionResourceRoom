function generalSearch(){
    var criteria = '';
    criteria = $(`#Criteria`).val();
    alert(criteria);
    document.location="../controller/controller.php?action=shopperHome&ListType=GeneralSearch&Criteria=" + encodeURIComponent(criteria);
}

function addToCart(button){
    alert(button.id);
}

function adjustAll(){
    var textBoxes = document.querySelectorAll('[id^=number]');

//    var ProductRow = "<?php echo $ProductRow['PRODUCTID']; ?>";
//    document.location="../Controller/Controller.php?action=processBulkStockAdjust&PRODUCTID=" + ProductRow;
}
