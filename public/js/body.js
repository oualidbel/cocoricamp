const nbTravelersInput = document.getElementById('nb-travelers');
const travelersWindow = document.getElementById('travelers-window');
const minusAdults = document.getElementById('minus-adults');
const minusChildren = document.getElementById('minus-children');
const plusAdults = document.getElementById('plus-adults');
const plusChildren = document.getElementById('plus-children');
const adultsInput = document.getElementById('form_adults');
const childrenInput = document.getElementById('form_children');

nbTravelersInput.addEventListener('click', () => {
    travelersWindow.classList.toggle('active');
});

document.addEventListener('click', function(e) {
    if (e.target.closest(".travelers")) return;
    if (e.target.closest(".nb-travelers")) return;
	travelersWindow.classList.remove("active");
    console.log('click');
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
    if (travelers == 8) {
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
    if (travelers < 8) {
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