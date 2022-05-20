const mobileNavMenu = document.querySelector('.mobile-nav-menu');
const mobileNavButton = document.querySelector('.mobile-nav-button');
const profilNavMenu = document.querySelector('.profil-nav-menu');

function mobileNavMenuFunction() {
    mobileNavMenu.classList.toggle('active');
    mobileNavButton.classList.toggle('button-active');
    if (profilNavMenu.classList.contains('active')) {
        profilNavMenu.classList.remove('active');
    }
}

function profilNavMenuFunction() {
    profilNavMenu.classList.toggle('active');
    if (mobileNavMenu.classList.contains('active')) {
        mobileNavMenu.classList.remove('active');
        mobileNavButton.classList.remove('button-active');
    }
}

document.addEventListener('click', function(e) {
    if (e.target.closest(".profil-nav-menu")) return;
    if (e.target.closest(".profil-nav-button")) return;
	profilNavMenu.classList.remove("active");
    
    if (e.target.closest(".mobile-nav-menu")) return;
    if (e.target.closest(".mobile-nav-button")) return;
	mobileNavMenu.classList.remove("active");
    mobileNavButton.classList.remove("button-active");
});