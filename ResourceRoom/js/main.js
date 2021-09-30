function generalSearch(){
    var criteria = '';
    criteria = $(`#Criteria`).val();
    alert(criteria);
    document.location="../controller/controller.php?action=shopperHome&ListType=GeneralSearch&Criteria=" + encodeURIComponent(criteria);
}

function addToCart(button){
    alert(button.id);
}
