function logoutDashboard() {
    if (confirm("Desconectar?"))
        window.location = HOME + "logout"
}

function hide_sidebar_small() {
    if (screen.width < 993) {
        $("#myOverlay, #mySidebar").css("display", "none");
    }
}

$(function () {
    $("#content, #app-sidebar").off("click", ".menu-li").on("click", ".menu-li", function () {
        var lib = $(this).attr("data-lib");
        var attr = $(this).attr("data-atributo");
        $(".main").loading();
        hide_sidebar_small();
        closeSidebar();
        if (lib === "") {
            post("table", "api", {entity: attr}, function (data) {
                $("#dashboard").html(data)
            })
        } else {
            get(attr, function (data) {
                $("#dashboard").html(data.content)
            })
        }
    }).off("click", "#btn-editLogin").on("click", "#btn-editLogin", function () {
        $(this).panel(
            themeDashboard("Editar Perfil", {lib: 'dashboard', file: 'edit/perfil'}, function (idOntab) {
                data = formGetData($("#" + idOntab).find(".ontab-content").find(".form-crud"));
                post('dashboard', 'edit/session', {dados: data}, function () {
                    location.reload()
                })
            })
        )
    });

    setTimeout(function () {
        get("dash/geral", function (data) {
            $("#dashboard").html(data.content);
            spaceHeader();
        })
    },300);
})