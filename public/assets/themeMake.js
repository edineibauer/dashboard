function huePlus(amount) {
    var x,y;
    x = document.getElementById("color").value;
    y = w3color(x).toHsl();
    y.h = y.h + amount;
    if (y.h < 0) {y.h = 355;}
    document.getElementById("color").value = w3color("hsl(" + y.h + "," + y.s + "," + y.l + ")").toHexString();
    createTheme();
}
function saturationPlus(amount) {
    var x,y;
    x = document.getElementById("color").value;
    y = w3color(x).toHsl();
    y.s = y.s + amount;
    if (y.s > 0.98) {y.s = 100;}
    if (y.s < 0.02) {y.s = 0.01;}
    document.getElementById("color").value = w3color("hsl(" + y.h + "," + y.s + "," + y.l + ")").toHexString();
    createTheme();
}
function createSuggestion(id) {
    let v = document.getElementById(id).value;
    let patt = new RegExp(/^#\w{6}$/i);
    if(patt.test(v)) {
        document.getElementById("color").value = document.getElementById(id).value;
        createTheme();
    }
}
function createTheme() {
    var theme1 = new Object;
    var i,c,h,s,l,b,v;
    var x, y, z, txt, sat, light, hex, col, ele;
    var y = document.getElementById("color").value;

    x = w3color(y).toHsl();

    document.getElementById("hue").value = x.h.toFixed(0);
    document.getElementById("saturation").value = x.s.toFixed(2);
    document.getElementById("color").value = w3color(y).toHexString();

    x = w3color(y).toHsl();
    sat = x.s;
    light = x.l;

    x.l= light + ((1.0-light)/5) * 4.7;
    hex = w3color("hsl(" + x.h + "," + sat + "," + x.l + ")").toHexString();
    z = document.getElementById("t1l5");
    z.style.backgroundColor = hex;
    z.innerHTML = hex + " theme-l5";
    theme1.l5 = hex;
    col = "#000";
    if (w3color(hex).isDark(165)) {col = "#fff"}
    z.style.color = col;
    theme1.tl5 = col;

    x.l= light + ((1.0-light)/5) * 4;
    hex = w3color("hsl(" + x.h + "," + sat + "," + x.l + ")").toHexString();
    z = document.getElementById("t1l4");
    z.style.backgroundColor = hex;
    z.innerHTML = hex + " theme-l4";
    theme1.l4 = hex;
    col = "#000";
    if (w3color(hex).isDark(165)) {col = "#fff"}
    z.style.color = col;
    theme1.tl4 = col;

    x.l= light + ((1.0-light)/5) * 3;
    hex = w3color("hsl(" + x.h + "," + sat + "," + x.l + ")").toHexString();
    z = document.getElementById("t1l3");
    z.style.backgroundColor = hex;
    z.innerHTML = hex + " theme-l3";
    theme1.l3 = hex;
    col = "#000";
    if (w3color(hex).isDark(165)) {col = "#fff"}
    z.style.color = col;
    theme1.tl3 = col;

    x.l= light + ((1.0-light)/5) * 2;
    hex = w3color("hsl(" + x.h + "," + sat + "," + x.l + ")").toHexString();
    z = document.getElementById("t1l2");
    z.style.backgroundColor = hex;
    z.innerHTML = hex + " theme-l2";
    theme1.l2 = hex;
    col = "#000";
    if (w3color(hex).isDark(165)) {col = "#fff"}
    z.style.color = col;
    theme1.tl2 = col;

    x.l= light + ((1.0-light)/5) * 1;
    hex = w3color("hsl(" + x.h + "," + sat + "," + x.l + ")").toHexString();
    z = document.getElementById("t1l1");
    z.style.backgroundColor = hex;
    z.innerHTML = hex + " theme-l1";
    theme1.l1 = hex;
    col = "#000";
    if (w3color(hex).isDark(165)) {col = "#fff"}
    z.style.color = col;

    theme1.tl1 = col;
    x.l= light;
    hex = w3color(y).toHexString();
    z = document.getElementById("t1d0");
    z.style.backgroundColor = hex;
    z.innerHTML = hex + " theme";
    theme1.d0 = hex;
    col = "#000";
    if (w3color(hex).isDark(165)) {col = "#fff"}
    z.style.color = col;
    theme1.td0 = col;

    x.l= light - ((light)/5) * 0.5;
    hex = w3color("hsl(" + x.h + "," + x.s + "," + x.l + ")").toHexString();
    z = document.getElementById("t1d1");
    z.style.backgroundColor = hex;
    z.innerHTML = hex + " theme-d1";
    theme1.d1 = hex;
    col = "#000";
    if (w3color(hex).isDark(165)) {col = "#fff"}
    z.style.color = col;
    theme1.td1 = col;

    x.l= light - ((light)/5) * 1;
    hex = w3color("hsl(" + x.h + "," + x.s + "," + x.l + ")").toHexString();
    z = document.getElementById("t1d2");
    z.style.backgroundColor = hex;
    z.innerHTML = hex + " theme-d2";
    theme1.d2 = hex;
    col = "#000";
    if (w3color(hex).isDark(165)) {col = "#fff"};
    z.style.color = col;
    theme1.td2 = col;

    x.l= light - ((light)/5) * 1.5;
    hex = w3color("hsl(" + x.h + "," + x.s + "," + x.l + ")").toHexString();
    z = document.getElementById("t1d3");
    z.style.backgroundColor = hex;
    z.innerHTML = hex + " theme-d3";
    theme1.d3 = hex;
    col = "#000";
    if (w3color(hex).isDark(165)) {col = "#fff"}
    z.style.color = col;
    theme1.td3 = col;

    x.l= light - ((light)/5) * 2;
    hex = w3color("hsl(" + x.h + "," + x.s + "," + x.l + ")").toHexString();
    z = document.getElementById("t1d4");
    z.style.backgroundColor = hex;
    z.innerHTML = hex + " theme-d4";
    theme1.d4 = hex;
    col = "#000";
    if (w3color(hex).isDark(165)) {col = "#fff"}
    z.style.color = col;
    theme1.td4 = col;

    x.l= light - ((light)/5) * 2.5;
    hex = w3color("hsl(" + x.h + "," + x.s + "," + x.l + ")").toHexString();
    z = document.getElementById("t1d5");
    z.style.backgroundColor = hex;
    z.innerHTML = hex + " theme-d5";
    theme1.d5 = hex;
    col = "#";
    if (w3color(hex).isDark(165)) {col = "#fff"}
    z.style.color = col;
    theme1.td5 = col;

    txt = ".theme-l5 {color:" + theme1.tl5 + " !important; background-color:" + theme1.l5 +" !important}<br>";
    txt += ".theme-l4 {color:" + theme1.tl4 + " !important; background-color:" + theme1.l4 +" !important}<br>";
    txt += ".theme-l3 {color:" + theme1.tl3 + " !important; background-color:" + theme1.l3 +" !important}<br>";
    txt += ".theme-l2 {color:" + theme1.tl2 + " !important; background-color:" + theme1.l2 +" !important}<br>";
    txt += ".theme-l1 {color:" + theme1.tl1 + " !important; background-color:" + theme1.l1 +" !important}<br>";
    txt += ".theme-d1 {color:" + theme1.td1 + " !important; background-color:" + theme1.d1 +" !important}<br>";
    txt += ".theme-d2 {color:" + theme1.td2 + " !important; background-color:" + theme1.d2 +" !important}<br>";
    txt += ".theme-d3 {color:" + theme1.td3 + " !important; background-color:" + theme1.d3 +" !important}<br>";
    txt += ".theme-d4 {color:" + theme1.td4 + " !important; background-color:" + theme1.d4 +" !important}<br>";
    txt += ".theme-d5 {color:" + theme1.td5 + " !important; background-color:" + theme1.d5 +" !important}<br><br>";

    txt += ".theme-light {color:" + theme1.tl5 + " !important; background-color:" + theme1.l5 +" !important}<br>";
    txt += ".theme-dark {color:" + theme1.td5 + " !important; background-color:" + theme1.d5 +" !important}<br>";
    txt += ".theme-action {color:" + theme1.td5 + " !important; background-color:" + theme1.d5 +" !important}<br><br>";

    txt += ".theme {color:" + theme1.td0 + " !important; background-color:" + theme1.d0 +" !important}<br>";
    txt += ".theme-aux {background-color:" + theme1.td0 + " !important}<br>";
    txt += ".theme-text-aux {color:" + theme1.td0 + " !important}<br>";
    txt += ".theme-text, input:focus ~ label {color:" + theme1.d0 + " !important}<br>";
    txt += ".theme-text-l {color:" + theme1.l2 + " !important}<br>";
    txt += ".theme-text-d {color:" + theme1.d2 + " !important}<br>";
    txt += ".theme-border {border-color:" + theme1.d0 + " !important}<br><br>";
    txt += ".theme-border-l {border-color:" + theme1.l2 + " !important}<br><br>";
    txt += ".theme-border-d {border-color:" + theme1.d2 + " !important}<br><br>";

    txt += ".theme-hover:hover {color:" + theme1.td0 + " !important; background-color:" + theme1.d0 +" !important}<br>";
    txt += ".theme-text-hover:hover {color:" + theme1.d0 + " !important}<br>";
    txt += ".theme-border-hover:hover {border-color:" + theme1.d0 + " !important}<br>";

    /* SWITCH STYLE */
    if(theme1.d0 !== "#ffffff") {
        txt += ".switch input:checked + .slider {background-color:" + theme1.l2 + "!important}<br>";
        txt += ".switch input:checked + .slider:after {background-color:" + theme1.d0 + "!important}<br>";

        /* INPUT BAR STYLE */
        txt += "input:focus, textarea:focus, select:focus, select {border-bottom-color:" + theme1.d0 + " !important}<br><br>";
        txt += ".input-bar:before, .input-bar:after, button {background-color:" + theme1.d0 + "!important}<br><br>";

        /* PAGINAÇÃO */
        txt += ".bar .button.active {background-color:" + theme1.d0 + "!important}<br><br>";

        /* TEXTAREA STYLE */
        txt += ".jqte_focused {border-bottom-color:" + theme1.d0 + " !important; border-left-color:" + theme1.d0 + " !important;}<br>";

        /* LOADING LINE STYLE */
        txt += "@keyframes loading {0% {left: 0;width: 0;background: " + theme1.d1 + ";}20% {width: 120px}100% {left: 100%;width: 0;background: " + theme1.d3 + "}}";
        txt += "@-webkit-keyframes loading {0% {left: 0;width: 0;background: " + theme1.d1 + ";}20% {width: 120px}100% {left: 100%;width: 0;background: " + theme1.d3 + "}}";
    }

    localStorage.setItem('txt', txt);

    ele = document.getElementById("mt1-top");
    ele.style.background = theme1.d3;
    ele.style.color = theme1.td3;

    ele = document.getElementById("mt1-header");
    ele.style.background = theme1.d0;
    ele.style.color = theme1.td0;

    ele = document.getElementById("mt1-footer");
    ele.style.background = theme1.d0;
    ele.style.color = theme1.td0;

    ele = document.getElementById("mt1-bottom");
    ele.style.background = theme1.d5;
    ele.style.color = theme1.td5;

    ele = document.getElementById("mt1-action");
    ele.style.background = theme1.d5;
    ele.style.color = theme1.td5;

    ele = document.getElementById("mt1-graphic");
    ele.style.color = theme1.d0;

    ele = document.getElementById("mt1-back");
    ele.style.background = theme1.l5;

    ele = document.getElementById("mt1-h1");
    ele.style.color = theme1.d0;
    ele = document.getElementById("mt1-h2");
    ele.style.color = theme1.d0;
    ele = document.getElementById("mt1-h3");
    ele.style.color = theme1.d0;

    displayDemo(theme1);
}

function restoreTheme() {
    toast("Restaurando Tema", 8000, "toast-success");
    post('dashboard', 'tema/restoreTheme', function (d) {
        if (d === "no") {
            toast("tema anterior não encontrado", 3000, "toast-warning");
        } else {
            $("link[href='" + HOME + "public/assets/theme.min.css?v=" + VERSION +"]'").attr("href", HOME + "public/assets/theme.min.css?v=99" + Math.ceil(Math.random() * 1000));
            location.href = HOME + "dashboard";
        }
    });
}

function saveTheme() {
    if(localStorage.txt) {
        toast("Aplicando Tema", 8000, "toast-success");
        post('dashboard', 'tema/saveTheme', {txt: localStorage.txt}, function () {
            $("link[href='" + HOME + "public/assets/theme.min.css?v=" + VERSION +"]'").attr("href", HOME + "public/assets/theme.min.css?v=99" + Math.ceil(Math.random() * 1000));
            location.href = HOME + "dashboard";
        });
    } else {
        toast("selecione um tema antes", 3000, "toast-warning");
    }
}

function displayDemo(theme) {
    document.getElementById("demo-h1").style.backgroundColor = theme.d2;
    document.getElementById("demo-h1").style.color = theme.td2;
    document.getElementById("demo-input").style.backgroundColor = theme.d1;
    document.getElementById("demo-h2").style.backgroundColor = theme.d0;
    document.getElementById("demo-h2").style.color = theme.td0;
}

localStorage.removeItem("txt");