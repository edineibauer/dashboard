function logoutDashboard(){
    if(confirm("Desconectar?"))
        window.location = HOME + "logout";
}

$(function () {
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

    $("#btn-editLogin").on("click", function () {
        $(this).panel(themeWindow("Editar Perfil", {lib: 'dashboard', file:'edit/perfil'}, function (idOntab) {
            data = formGetData($("#" + idOntab).find(".ontab-content").find(".form-crud"));
            post('dashboard', 'edit/session', {dados: data}, function () {
                location.reload();
            });
        }));
    });
});