let service = document.querySelector('.services');
let image = document.querySelector('.services img');

let counter = 1;



// Modifie / manipule les éléments

function carousel() {
    if (counter ==5) {
        counter = 0;
    }
    counter++;
    console.log(service, image);
    
    image.src = `../images/services${counter}.jpg`;

};
