function saveConfig(field, value) {
    post("dashboard", "settings/saveConfig", {field: field, value: value}, function (g) {
        if (g)
            toast("erro", "warning");
    });
}

function saveConfigBase(dados) {
    $.each(dados, function (i, e) {
        if (i !== "dados.id")
            saveConfig(i.replace("dados.", "").replace("nome_do_site", "sitename").replace("subtitulo", "sitesub").replace("descricao", "sitedesc"), e);
    });
}

$(function () {
    $("#reautorar").off("click").on("click", function () {
        post("dashboard", "settings/autor", {autor: $("#selectReautor").val()}, function (g) {
            toast("Salvo");
        });
    });

    $("#protocol").off("change").on("change", function () {
        saveConfig("protocol", $(this).prop("checked") ? "https://" : "http://");
    });

    $(".inputConfig").off("keyup change").on("keyup change", function () {
        saveConfig($(this).attr("id"), $(this).val());
    });
});