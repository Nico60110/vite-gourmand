const avisCards = document.querySelectorAll(".avis-card");
const prevAvis = document.getElementById("prevAvis");
const nextAvis = document.getElementById("nextAvis");

let avisIndex = 0;

function afficherAvis() {
    avisCards.forEach(card => card.classList.remove("active"));
    avisCards[avisIndex].classList.add("active");
}

nextAvis.addEventListener("click", function () {
    avisIndex++;

    if (avisIndex >= avisCards.length) {
        avisIndex = 0;
    }

    afficherAvis();
});

prevAvis.addEventListener("click", function () {
    avisIndex--;

    if (avisIndex < 0) {
        avisIndex = avisCards.length - 1;
    }

    afficherAvis();
});