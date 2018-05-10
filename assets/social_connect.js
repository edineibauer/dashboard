function saveSocialSettings(field, value) {
    post("dashboard", "settings/saveConfig", {field: field, value: value}, function (g) {
        if (g)
            toast("erro", "warning");
    });
}

$(function () {
    $(".inputConfig").off("keyup change").on("keyup change", function () {
        saveSocialSettings($(this).attr("id"), $(this).val());
    });
});