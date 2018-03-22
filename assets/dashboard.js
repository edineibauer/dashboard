function logoutDashboard(){
    if(confirm("Desconectar?"))
        window.location = HOME + "logout";
}

$(function () {
    var change = false;
    $(".btn-entity").on("click", function () {
        $(".dashboard-tab").addClass("hide");
        $("#entity").removeClass("hide");
        $(".main").loading();

        post("table", "api", {entity: $(this).attr("data-entity")}, function (data) {
            $("#entity").html(data);
        });
    });

    $("#btn-geral").on("click", function () {
        $(".dashboard-tab").addClass("hide");
        $("#dashboard").removeClass("hide");
    });

    $("#btn-settings").on("click", function () {
        $(".dashboard-tab").addClass("hide");
        $("#settings").removeClass("hide");
    });

    $("#btn-editLogin").on("click", function () {
        $(this).panel(themeWindow("Editar Perfil", {lib: 'dashboard', file:'edit/perfil'}, function (idOntab) {
            data = formGetData($("#" + idOntab).find(".ontab-content").find(".form-crud"));
            post('dashboard', 'edit/session', {dados: data}, function () {
                location.reload();
            });
        }));
    });

    $("#routes-settings").off("change", "input[type=checkbox]").on("change", "input[type=checkbox]", function () {
        var routeNew = [];
        $.each($("#routes-settings").find("input[type=checkbox]"), function () {
            if($(this).prop("checked"))
                routeNew.push($(this).val());
        });
        if(!change) {
            change = true;
            post('dashboard', 'settings/route', {route: routeNew}, function () {
                change = false;
            });
        }
    });

    $("#spacekey").off("change keyup").on("change keyup", function () {
        if(!change) {
            change = true;
            post('dashboard', 'settings/space', {key: $(this).val()}, function () {
                change = false;
            });
        }
    });
    $("#dev").off("change").on("change", function () {
        if(!change) {
            change = true;
            post('dashboard', 'settings/debug', {key: $(this).prop("checked")}, function () {
                change = false;
            });
        }
    });

    $("#clear-global").on("click", function () {
        post("dashboard", "cache/global", {}, function (g) {
            if(g==="1") {
                toast("Cache Limpo! Recarregando Arquivos...", 4000);
                setTimeout(function () {
                    location.reload();
                },700);
            }
        });
    });
});