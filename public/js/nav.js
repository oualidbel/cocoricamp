// Navbar

const nav = document.querySelector('.nav');
const mobileNavMenu = document.querySelector('.mobile-nav-menu');
const mobileNavButton = document.querySelector('.mobile-nav-button');
const mobileNavLinks = document.querySelectorAll('.mobile-nav-links');
const profilNavMenu = document.querySelector('.profil-nav-menu');

function mobileNavMenuFunction() {
    mobileNavMenu.classList.toggle('active');
    mobileNavButton.classList.toggle('button-active');
    mobileNavLinks.forEach(link => {
        link.classList.toggle('active');
    });
    if (profilNavMenu.classList.contains('active')) {
        profilNavMenu.classList.remove('active');
    }
    else if (mobileNavMenu.classList.contains('active')) {
        console.log(document.getElementsByTagName('body')[0].style.overflow = 'hidden');
    }
    else {
        console.log(document.getElementsByTagName('body')[0].style.overflow = 'auto');
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

let oldValue = 0;
let newValue = 0;

window.addEventListener('scroll', () => {
    newValue = window.pageYOffset;
    if (oldValue + 20 < newValue) {
        nav.style.top = `${-nav.offsetHeight}px`;
    } else if (oldValue > newValue + 20) {
        nav.style.top = '0';
    }
    oldValue = newValue;
});