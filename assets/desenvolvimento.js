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

    $("#clear-cache").off("click").on("click", function () {
        toast("Atualizando Sistema...", 3000);
        post("dashboard", "cache/update", {}, function (g) {
            if (g === "1") {
                toast("Recarregando Arquivos...", 4000);
                setTimeout(function () {
                    location.reload();
                }, 700);
            }
        });
    });

    $("#clear-global").off("click").on("click", function () {
        toast("Atualizando Assets", 2000);
        post("dashboard", "cache/global", {}, function (g) {
            if (g === "1") {
                toast("Recarregando Assets...", 4000);
                setTimeout(function () {
                    location.reload();
                }, 700);
            }
        });
    });
});