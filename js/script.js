function updateChamp() {
    var champ1 = document.getElementById("type_person");
    if (champ1.value === "company") {
        document.getElementById('username').placeholder='Username';
    } else {
        document.getElementById('username').placeholder='Mail';
    }
}