function generalSearch(){
    var criteria = '';
    criteria = $(`#Criteria`).val();
    document.location="../controller/controller.php?action=shopperHome&ListType=GeneralSearch&Criteria=" + encodeURIComponent(criteria);
}

