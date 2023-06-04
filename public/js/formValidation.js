// formValidation.js

function validateUsername(username) {
    if (username.value.length < 4) {
        username.setCustomValidity('Le nom d\'utilisateur doit comporter au moins 4 caractères.');
        // showToast('Le nom d\'utilisateur doit comporter au moins 4 caractères.', 'error');
    } else if (username.value.length > 25) {
        username.setCustomValidity('Le nom d\'utilisateur doit comporter au plus 25 caractères.');
        showToast('Le nom d\'utilisateur doit comporter au plus 25 caractères.', 'info');
    } else if (/\d/.test(username.value)) {
        username.setCustomValidity('Le nom d\'utilisateur ne peut pas contenir de nombres.');
        showToast('Le nom d\'utilisateur ne peut pas contenir de nombres.', 'info');
    } else if (/[^\p{L} ]/u.test(username.value)) {
        username.setCustomValidity('Le nom d\'utilisateur ne peut pas contenir de symboles.');
        showToast('Le nom d\'utilisateur ne peut pas contenir de symboles.', 'info');
    } else {
        username.setCustomValidity('');
    }
}

function validatePassword(password) {
    if (password.value.length < 6) {
        password.setCustomValidity('Le mot de passe doit comprendre au moins 6 caractères.');
        // showToast('Le mot de passe doit comprendre au moins 6 caractères.', 'error');
    } else if (password.value.length > 20) {
        password.setCustomValidity('Le mot de passe doit comprendre au plus 20 caractères.');
        showToast('Le mot de passe doit comprendre au plus 20 caractères.', 'info');
    } else if (!/[A-Z]/.test(password.value)) {
        password.setCustomValidity('Le mot de passe doit inclure au moins une lettre majuscule.');
        showToast('Le mot de passe doit inclure au moins une lettre majuscule.', 'info');
    } else if (!/\d/.test(password.value)) {
        password.setCustomValidity('Le mot de passe doit inclure au moins un chiffre.');
        showToast('Le mot de passe doit inclure au moins un chiffre.', 'info');
    } else if (/[<>=\/\\]/.test(password.value)) {
        password.setCustomValidity('Le mot de passe ne peut pas contenir <, >, \', =, /, \\ .');
        showToast('Le mot de passe ne peut pas contenir <, >, \', =, /, \\ .', 'info');
    } else if (/\s/.test(password.value)) {
        password.setCustomValidity('Le mot de passe ne peut pas contenir d\'espace.');
        showToast('Le mot de passe ne peut pas contenir d\'espace.', 'info');
    } else {
        password.setCustomValidity('');
    }
}


function validateDescription(description) {

    if (/[<>=\/\\]/.test(description.value)) {
        description.setCustomValidity('La description ne peut pas contenir les symboles suivants: <, >, =, /, \\.');
        showToast('La description ne peut pas contenir les symboles suivants: <, >, =, /, \\.', 'info');
    } else if (description.value.length > 300) {
        description.setCustomValidity('La longueur de la description doit être comprise entre 20 et 300 caractères.');
        showToast('La longueur de la description doit être comprise entre 20 et 300 caractères.', 'info');


    } else {
        description.setCustomValidity('');
    }
}
function parseDate(input) {
    let parts = input.split('-');
    return new Date(parts[2], parts[1] - 1, parts[0]);
}

function validateDate(date) {
    let currentDate = new Date();
    let inputDate = parseDate(date.value);
    if (inputDate.setHours(0, 0, 0, 0) < currentDate.setHours(0, 0, 0, 0)) {
        date.setCustomValidity('La date ne peut pas être dans le passé.');
        showToast('La date ne peut pas être dans le passé.', 'info');
    } else {
        date.setCustomValidity('');
    }
}

function convertTimeToMinutes(timeStr) {
    const [hours, minutes] = timeStr.split(':').map(Number);
    return (hours * 60) + minutes;
}

function validateTime(time, minHour = '06:00', maxHour = '22:00') {
    const timeInMinutes = convertTimeToMinutes(time.value);
    const minHourInMinutes = convertTimeToMinutes(minHour);
    const maxHourInMinutes = convertTimeToMinutes(maxHour);

    if (timeInMinutes < minHourInMinutes || timeInMinutes > maxHourInMinutes) {
        time.setCustomValidity(`L'heure doit être comprise entre ${minHour} et ${maxHour}.`);
        showToast(`L'heure doit être comprise entre ${minHour} et ${maxHour}.`, 'info');
    } else {
        time.setCustomValidity('');
    }
}

window.addEventListener('load', function () {
    let form = document.querySelector('form');

    let username = document.getElementById('username');
    let usernameUpt = document.getElementById('usernameUpdate');

    let password = document.getElementById('password');
    let passwordUpt = document.getElementById('passwordUpdate');

    let description = document.getElementById('description');
    let descriptionUpt = document.getElementById('descriptionUpdate');

    let date = document.getElementById('date');
    let new_date = document.getElementById('new_date');

    let start_hour = document.getElementById('start_hour');
    let end_hour = document.getElementById('end_hour');

    let start_hourUpt = document.getElementById('start_hourUpdate');
    let end_hourUpt = document.getElementById('end_hourUpdate');
    username.addEventListener('input', function () {
        validateUsername(username);
    });

    password.addEventListener('input', function () {
        validatePassword(password);
    });

    description.addEventListener('input', function () {
        validateDescription(description);
    });

    date.addEventListener('input', function () {
        validateDate(date);
    });

    start_hour.addEventListener('input', function () {
        validateTime(start_hour);
    });

    end_hour.addEventListener('input', function () {
        validateTime(end_hour);
    });

    usernameUpt.addEventListener('input', function () {
        validateUsername(usernameUpt);
    });

    descriptionUpt.addEventListener('input', function () {
        validateDescription(descriptionUpt);
    });

    new_date.addEventListener('input', function () {
        validateDate(new_date);
    });

    start_hourUpt.addEventListener('input', function () {
        validateTime(start_hourUpt);
    });

    end_hourUpt.addEventListener('input', function () {
        validateTime(end_hourUpt);
    });
    end_hour.addEventListener('input', function () {
        validateTime(end_hourUpt);
    });

    form.addEventListener('submit', function (event) {
        validateUsername(username);
        validatePassword(password);
        validateDescription(description);
        validateDate(date);
        validateTime(start_hour);
        validateTime(end_hour);
         validateDescription(descriptionUpt);
        validateDate(new_date);
        validateTime(start_hourUpt);
        validateTime(end_hourUpt);

        if (!username.validity.valid || !password.validity.valid || !description.validity.valid ||
            !date.validity.valid || !start_hour.validity.valid || !end_hour.validity.valid ) {
            event.preventDefault();
        }
    });
});