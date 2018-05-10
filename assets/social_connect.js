function saveSocialSettings(field, value) {
    post("dashboard", "settings/saveConfig", {field: field, value: value}, function (g) {
        if (g)
            toast("erro", "warning")
    })
}

function checkIfButtonConnectExist(id) {
    if (!$(id).length) {
        post("dashboard", "social_connect/checkButtonConnect", {id: id}, function (g) {
            if (g)
                $("#space-" + id.substr(1,id.length)).html(g);
        });
    }
}

$(function () {
    $(".inputConfig").off("keyup change").on("keyup change", function () {
        if ($(this).val().length === 0)
            $("#" + $(this).attr("rel")).remove();
        else
            checkIfButtonConnectExist("#" + $(this).attr("rel"));

        saveSocialSettings($(this).attr("id"), $(this).val());
    });

    $(".space-btn-social-connect").off("click", "button-connect-social").on("click", ".button-connect-social", function (e) {
        var $this = $(this);
        post("dashboard", "social_connect/getPost", {social:$this.attr("rel")}, function (g) {
            if(!g)
                toast("Posts do " + ucFirst($this.attr("rel")) + " foram Atualizados", 4000);
            else
                location.href = g;
        });
    });
});