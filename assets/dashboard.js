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
});