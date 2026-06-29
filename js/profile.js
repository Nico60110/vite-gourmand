const btnModifier = document.getElementById("btnModifier");
const btnEnregistrer = document.getElementById("btnEnregistrer");

btnModifier.addEventListener("click", function () {

    document.querySelectorAll("form input").forEach(input => {
        input.removeAttribute("readonly");
    });

    btnModifier.style.display = "none";
    btnEnregistrer.style.display = "inline-block";

});