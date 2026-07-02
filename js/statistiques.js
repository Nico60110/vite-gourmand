const ctx = document.getElementById("graphique");

new Chart(ctx, {

    type: "bar",

    data: {

        labels: labels,

        datasets: [{

            label: "Nombre de commandes",

            data: nbCommandes,

            borderWidth: 1

        }]

    },

    options: {

        responsive: true,

        scales: {

            y: {

                beginAtZero: true,

                ticks: {

                    precision: 0

                }

            }

        }

    }

});