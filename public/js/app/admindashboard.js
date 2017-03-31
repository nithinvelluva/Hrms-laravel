function navigateTo(page) {
    var activeElement;
    var activeContentDiv;
    switch (page) {
        case DASHBOARD:
            activeElement = DASHBOARD_LI;
            activeContentDiv = DASHBOARD_DIV;
            break;
        case USERS:
            blockNumber = 1;
            $('#cardContainer').empty();
            activeElement = USERS_LI;
            activeContentDiv = USERS_DIV;
            showLoad();
            UpdateEmpTable();
            clearValidationError();
            break;
    }

    $('.contentDiv').hide();
    $(activeContentDiv).show();

    $(".main-nav li").removeClass("active");
    $(activeElement).addClass("active");
}
$(document).ready(function () {    
    navigateTo(DASHBOARD);
});
