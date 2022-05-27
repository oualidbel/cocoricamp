let image = document.querySelector('.services-img');
const servicesContentItems = document.querySelectorAll('.services-content-item');

let counter = 0;

// Modifie / manipule les éléments

function changeImageNext() {
    if (counter == 3) {
        counter = -1;
        counter++;
        image.style.backgroundImage = `url(../images/services${counter}.jpg)`;
        servicesContentItems[3].classList.remove('active');
        servicesContentItems[0].classList.add('active');
    } else {
        counter++;
        image.style.backgroundImage = `url(../images/services${counter}.jpg)`;
        servicesContentItems[counter-1].classList.remove('active');
        servicesContentItems[counter].classList.add('active');
    }
};


function changeImagePrevious() {
    if (counter == 0) {
        counter = 4;
        counter--;
        image.style.backgroundImage = `url(../images/services${counter}.jpg)`;
        servicesContentItems[0].classList.remove('active');
        servicesContentItems[3].classList.add('active');
    } else {
        counter--;
        image.style.backgroundImage = `url(../images/services${counter}.jpg)`;
        servicesContentItems[counter+1].classList.remove('active');
        servicesContentItems[counter].classList.add('active');
    }
};