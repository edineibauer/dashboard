function logoutDashboard() {
    if (confirm("Desconectar?"))
        window.location = HOME + "logout"
}

function hide_sidebar_small() {
    if (screen.width < 993) {
        $("#myOverlay, #mySidebar").css("display", "none");
    }
}

function clearMenu() {
    $(".main").loading();
    hide_sidebar_small();
    closeSidebar();
}

$(function () {
    $("#content, #app-sidebar").off("click", ".menu-li").on("click", ".menu-li", function () {
        let action = $(this).attr("data-action");
        if (action === "table") {
            var param = {
                entity: $(this).attr("data-entity"),
                relation: $(this).attr("data-relation"),
                column: $(this).attr("data-column"),
                type: $(this).attr("data-type"),
                id: $(this).attr("data-id")
            }
            post("table", "api", param, function (data) {
                $("#dashboard").html(data)
            })
        } else if(action === 'form') {
            post("form-crud", "api", {entity: attr}, function (data) {
                $("#dashboard").html(data)
            })

        } else if(action === 'page') {
            var lib = $(this).attr("data-lib");
            var attr = $(this).attr("data-atributo");
            get(attr, function (data) {
                $("#dashboard").html(data.content)
            })

        } else if(action === 'link') {
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
    }, 300);
})