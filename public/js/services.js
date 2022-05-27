let image = document.querySelector('.services-img');

let counter = 1;


console.log('test');

// Modifie / manipule les éléments

image.addEventListener('click', function() {
    if (counter == 5) {
        counter = 0;
    } 
    counter++;
    image.style.backgroundImage = `url(../images/services${counter}.jpg)`;
    console.log(image.style.backgroundImage = `url(../images/services${counter}.jpg)`);
});
