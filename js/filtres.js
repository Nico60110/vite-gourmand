const form = document.getElementById("filtreForm");

form.addEventListener("input", chargerMenus);

function chargerMenus() {

    const data = new FormData(form);

    const params = new URLSearchParams(data);

    fetch("filtre-menu.php?" + params.toString())

        .then(response => response.text())

        .then(html => {

            document.getElementById("listeMenus").innerHTML = html;

        });

}