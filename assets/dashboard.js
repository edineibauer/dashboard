function hide_sidebar_small() {
    if (screen.width < 993)
        close_sidebar();
}

$(function () {
    $("#content").off("click", ".menu-li").on("click", ".menu-li", function () {
        $("#content .menu-li").removeClass("color-grey-light");
        $(this).addClass("color-grey-light");
        var lib = $(this).attr("data-lib");
        var attr = $(this).attr("data-atributo");
        $(".main").loading();
        hide_sidebar_small();

        if(lib === "") {
            post("table", "api", {entity: attr}, function (data) {
                $("#dashboard").html(data);
                spaceHeader();
            });
        } else {
            get(attr, function (data) {
                $("#dashboard").html(data.content);
                spaceHeader();
            });
        }

    }).off("click", "#btn-editLogin").on("click", "#btn-editLogin", function () {
        $(this).panel(themeWindow("Editar Perfil", {lib: 'dashboard', file: 'edit/perfil'}, function (idOntab) {
            data = formGetData($("#" + idOntab).find(".ontab-content").find(".form-crud"));
            post('dashboard', 'edit/session', {dados: data}, function () {
                location.reload();
            });
        }));

    });

    get("dash/geral", function (data) {
        $("#dashboard").html(data);
        spaceHeader();
    });
});