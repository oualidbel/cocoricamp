const nbTravelersInput = document.getElementById('nb-travelers');
const travelersWindow = document.getElementById('travelers-window');
const minusAdults = document.getElementById('minus-adults');
const minusChildren = document.getElementById('minus-children');
const plusAdults = document.getElementById('plus-adults');
const plusChildren = document.getElementById('plus-children');
const adultsInput = document.getElementById('form_adults');
const childrenInput = document.getElementById('form_children');
const hostCapacity = parseInt(document.getElementById('host-capacity').innerHTML.slice(-1));

console.log(hostCapacity);

nbTravelersInput.addEventListener('click', () => {
    travelersWindow.classList.toggle('active');
});

document.addEventListener('click', function(e) {
    if (e.target.closest(".travelers")) return;
    if (e.target.closest(".nb-travelers")) return;
	travelersWindow.classList.remove("active");
});

function add(input, button) {
    if (input.value < input.max) {
        input.value++;
        if (input.value == input.max) {
            button.disabled = true;
        }
        else if (button.id === 'plus-adults') {
            minusAdults.disabled = false;
        }
        else if (button.id === 'plus-children') {
            minusChildren.disabled = false;
            if (adultsInput.value == 0) {
                adultsInput.value = 1;
            } else if (adultsInput.value == 1) {
                minusAdults.disabled = true;
            }
        }
    }
    var travelers = parseInt(adultsInput.value) + parseInt(childrenInput.value);
    if (travelers == hostCapacity) {
        plusAdults.disabled = true;
        plusChildren.disabled = true;
        nbTravelersInput.value = `${travelers} voyageurs`;
    }
    else if (travelers == 0) {
        nbTravelersInput.value = 'Qui ?';
    } 
    else if (travelers == 1) {
        nbTravelersInput.value = '1 voyageur';
    }
    else {
        nbTravelersInput.value = `${travelers} voyageurs`;
    }
}

function substract(input, button) {
    if (input.value > input.min) {
        input.value--;
        if (input.value == input.min) {
            button.disabled = true;
            if (adultsInput.value == 1) {
                minusAdults.disabled = false;
            }
        } 
        else if (childrenInput.value > 0 && adultsInput.value == 1) {
            minusAdults.disabled = true;
        }
        else if (button.id === 'minus-children') {
            plusChildren.disabled = false;
        }
        else if (button.id === 'minus-adults') {
            plusAdults.disabled = false;
        }
    } 
    var travelers = parseInt(adultsInput.value) + parseInt(childrenInput.value);
    if (travelers < hostCapacity) {
        plusAdults.disabled = false;
        plusChildren.disabled = false;
        if (travelers == 0) {
            nbTravelersInput.value = 'Qui ?';
        } 
        else if (travelers == 1) {
            nbTravelersInput.value = '1 voyageur';
        }
        else {
            nbTravelersInput.value = `${travelers} voyageurs`;
        }
    }
}

const formCheckIn = document.getElementById('form_check_in');
const formCheckOut = document.getElementById('form_check_out');
const pricePerNight = parseInt(document.getElementById('price').innerHTML.split('€')[0]);

formCheckIn.addEventListener('change', () => {
    var date = new Date(formCheckIn.value);
    date.setDate(date.getDate() + 1);
    formCheckOut.min = date.toISOString().split('T')[0];
    formCheckOut.value = date.toISOString().split('T')[0];
});

formCheckOut.addEventListener('change', () => {
    var checkInDate = new Date(formCheckIn.value);
    var checkOutDate = new Date(formCheckOut.value);
    var nbNights = Math.ceil((checkOutDate - checkInDate) / (1000 * 60 * 60 * 24));
    document.getElementById('price').innerHTML = `${nbNights * pricePerNight}€<span>/${nbNights} nuits</span>`;
});