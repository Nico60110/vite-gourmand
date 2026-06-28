const checkbox = document.getElementById("pretMateriel");
const listeMateriel = document.getElementById("listeMateriel");

checkbox.addEventListener("change", function () {

    if (this.checked) {
        listeMateriel.classList.remove("cache");
    } else {
        listeMateriel.classList.add("cache");
    }

});