const nbPersonnes = document.getElementById("nbPersonnes");

const prixMenuElt = document.getElementById("prixMenu");
const reductionElt = document.getElementById("reduction");
const totalElt = document.getElementById("prixTotal");

nbPersonnes.addEventListener("input", function () {

    let nb = parseInt(this.value);

    if (isNaN(nb) || nb < nbMin) {
        nb = nbMin;
    }

    let prixMenu = prixParPersonne * nb;

    let reduction = 0;

    if (nb >= nbMin + 5) {
        reduction = prixMenu * 0.10;
    }

    let total = prixMenu - reduction + prixLivraison;

    prixMenuElt.textContent = prixMenu.toFixed(2) + " €";
    reductionElt.textContent = "-" + reduction.toFixed(2) + " €";
    totalElt.textContent = total.toFixed(2) + " €";

});