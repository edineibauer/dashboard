function saveConfig(field, value) {
    post("dashboard", "settings/saveConfig", {field: field, value: value}, function (g) {
        if (g)
            toast("erro", "warning")
    })
}

$(function () {
    $("#reautorar").off("click").on("click", function () {
        post("dashboard", "settings/autor", {autor: $("#selectReautor").val()}, function (g) {
            toast("Salvo")
        })
    });

    $(".inputConfig").off("keyup change").on("keyup change", function () {
        saveConfig($(this).attr("id"), $(this).val())
    });

    $("#spacekey").off("change keyup").on("change keyup", function () {
        if (!change) {
            change = true;
            post('dashboard', 'settings/space', {key: $(this).val()}, function () {
                change = false;
            });
        }
    });
});