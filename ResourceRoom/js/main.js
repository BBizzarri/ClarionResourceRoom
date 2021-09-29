function generalSearch(){
    let criteria = '';
    criteria = $('#Criteria').val();
    document.location = "../controller/controller.php?action=shopperHome&ListType=GeneralSearch&Criteria=" +
        encodeURIComponent(criteria);
}