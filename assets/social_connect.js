function saveSocialSettings(field, value) {
    post("dashboard", "settings/saveConfig", {field: field, value: value}, function (g) {
        if (g)
            toast("erro", "warning")
    })
}

function checkIfButtonConnectExist(id) {
    if (!$(id).length) {
        post("dashboard", "social_connect/checkButtonConnect", {id: id}, function (g) {
            if (g)
                $("#space-" + id.substr(1,id.length)).html(g);
        });
    }
}

$(function () {
    $(".inputConfig").off("keyup change").on("keyup change", function () {
        if ($(this).val().length === 0)
            $("#" + $(this).attr("rel")).remove();
        else
            checkIfButtonConnectExist("#" + $(this).attr("rel"));

        saveSocialSettings($(this).attr("id"), $(this).val());
    });
})