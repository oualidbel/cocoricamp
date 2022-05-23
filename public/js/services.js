let service = document.querySelector('.services');
let image = document.querySelector('.services img');

let counter = 1;


console.log(service, image);

// Modifie / manipule les éléments

service.addEventListener('click', function() {
    if (counter ==5) {
        counter = 0;
    }
    counter++;
   
    image.src = 'public/images/services' + counter + '.jpg';

});
