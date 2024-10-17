function updateChamp() {
    var champ1 = document.getElementById("type_person");
    if (champ1.value === "company") {
        document.getElementById('username').placeholder='Username';
    } else {
        document.getElementById('username').placeholder='Mail';
    }
}

function hide_or_show() {
    var x = document.getElementById("text_full");
    var y = document.getElementById("part_text");
    if (x.style.display === "none") {
        document.getElementById("toggle_see").innerHTML = "Hide"
        x.style.display = "block";
        y.style.display = "none";
    } else {
        document.getElementById("toggle_see").innerHTML = "See more"
        x.style.display = "none";
        y.style.display = "block";
    }
}
