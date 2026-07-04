const platSlides = document.querySelectorAll(".plat-slide");
const prevPlat = document.getElementById("prevPlat");
const nextPlat = document.getElementById("nextPlat");

let platIndex = 0;

if (platSlides.length > 0 && prevPlat && nextPlat) {

    function afficherPlat() {
        platSlides.forEach(slide => slide.classList.remove("active"));
        platSlides[platIndex].classList.add("active");
    }

    nextPlat.addEventListener("click", function () {
        platIndex++;

        if (platIndex >= platSlides.length) {
            platIndex = 0;
        }

        afficherPlat();
    });

    prevPlat.addEventListener("click", function () {
        platIndex--;

        if (platIndex < 0) {
            platIndex = platSlides.length - 1;
        }

        afficherPlat();
    });
}