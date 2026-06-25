const dropdownBtn = document.querySelector('.dropdown-btn');

if (dropdownBtn) {

    dropdownBtn.addEventListener('click', function () {

        document
            .querySelector('.dropdown')
            .classList.toggle('active');

    });

}