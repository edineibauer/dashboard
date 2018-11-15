var change = false;

$(function () {
    $("#routes-settings").off("change", "input[type=checkbox]").on("change", "input[type=checkbox]", function () {
        var routeNew = [];
        $.each($("#routes-settings").find("input[type=checkbox]"), function () {
            if ($(this).prop("checked"))
                routeNew.push($(this).val());
        });
        if (!change) {
            change = true;
            post('dashboard', 'settings/route', {route: routeNew}, function () {
                change = false;
            });
        }
    });

    $("#reautorar").off("click").on("click", function () {
        post("dashboard", "settings/autor", {autor: $("#selectReautor").val()}, function (g) {
            toast("Salvo")
        })
    });

    $("#envelopar-lib").off("click").on("click", function () {
        toast("Envelopando...", 3000);
        post("dashboard", "settings/enveloparBiblioteca", {}, function (g) {
            if (g === "1") {
                toast("Tudo Pronto!", 2000);
            } else{
                toast("Erro ao envelopar", 3000, "toast-error");
            }
        });
    });

    $("#clear-cache").off("click").on("click", function () {
        toast("Atualizando Sistema...", 3000);
        post("dashboard", "cache/update", {}, function (g) {
            toast("Recarregando Arquivos...", 4000);
            setTimeout(function () {
                location.reload();
            }, 700);
        });
    });

    $("#clear-global").off("click").on("click", function () {
        toast("Atualizando Assets", 2000);
        post("dashboard", "cache/global", {}, function (g) {
            toast("Recarregando Assets...", 4000);
            setTimeout(function () {
                location.reload();
            }, 700);
        });
    });
});