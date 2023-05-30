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

window.addEventListener('load', function () {
    let form = document.querySelector('form');

    let username = document.getElementById('username');
    let password = document.getElementById('password');

    // Check validity on input
    username.addEventListener('input', function () {
        validateUsername(username);
    });

    password.addEventListener('input', function () {
        validatePassword(password);
    });

    // Check validity on submit
    form.addEventListener('submit', function (event) {
        validateUsername(username);
        validatePassword(password);

        if (!username.validity.valid || !password.validity.valid) {
            event.preventDefault();
        }
    });
});
