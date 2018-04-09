var mySidebar = document.getElementById("mySidebar");
var overlayBg = document.getElementById("myOverlay");

function logoutDashboard() {
    if (confirm("Desconectar?"))
        window.location = HOME + "logout";
}

function open_sidebar() {
    if (mySidebar.style.display === 'block') {
        mySidebar.style.display = 'none';
        overlayBg.style.display = "none";
    } else {
        mySidebar.style.display = 'block';
        overlayBg.style.display = "block";
    }
}

function close_sidebar() {
    mySidebar.style.display = "none";
    overlayBg.style.display = "none";
}

function hide_sidebar_small() {
    if (screen.width < 993)
        close_sidebar();
}

$(function () {

    var change = false;
    $(".menu-li").off("click").on("click", function () {
        var lib = $(this).attr("data-lib");
        var attr = $(this).attr("data-atributo");
        $(".main").loading();
        hide_sidebar_small();

        if(lib === "") {
            post("table", "api", {entity: attr}, function (data) {
                $("#dashboard").html(data);
            });
        } else {
            post(lib, attr, {}, function (data) {
                $("#dashboard").html(data);
            });
        }
    });

    $("#btn-editLogin").off("click").on("click", function () {
        $(this).panel(themeWindow("Editar Perfil", {lib: 'dashboard', file: 'edit/perfil'}, function (idOntab) {
            data = formGetData($("#" + idOntab).find(".ontab-content").find(".form-crud"));
            post('dashboard', 'edit/session', {dados: data}, function () {
                location.reload();
            });
        }));
    });

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

    $("#spacekey").off("change keyup").on("change keyup", function () {
        if (!change) {
            change = true;
            post('dashboard', 'settings/space', {key: $(this).val()}, function () {
                change = false;
            });
        }
    });
    $("#dev").off("change").on("change", function () {
        if (!change) {
            change = true;
            post('dashboard', 'settings/debug', {key: $(this).prop("checked")}, function () {
                change = false;
            });
        }
    });

    $("#clear-global").off("click").on("click", function () {
        post("dashboard", "cache/global", {}, function (g) {
            if (g === "1") {
                toast("Cache Limpo! Recarregando Arquivos...", 4000);
                setTimeout(function () {
                    location.reload();
                }, 700);
            }
        });
    });

    $("#reautorar").off("click").on("click", function () {
        post("dashboard", "settings/autor", {autor: $("#selectReautor").val()}, function (g) {
            toast("Salvo");
        });
    });

    $(".allow-session").off("change").on("change", function () {
        post("dashboard", "settings/sessionAllow", {
            session: $(this).attr("rel"),
            entity: $(this).val(),
            action: $(this).prop("checked")
        }, function () {
        });
    });
});