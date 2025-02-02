document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector('form');
    if (!form) return;

    const emailInput = form.querySelector('input[name="email"]');
    const passwordInput = form.querySelector('input[name="password"]');
    const confirmedPasswordInput = form.querySelector('input[name="confirmedPassword"]');

    function isEmail(email) {
        return /\S+@\S+\.\S+/.test(email);
    }
    function arePasswordsSame(password, confirmedPassword) {
        return password === confirmedPassword;
    }

    function markValidation(element, condition) {
        !condition ? element.classList.add('no-valid') : element.classList.remove('no-valid');
    }

    emailInput.addEventListener('keyup', function () {
        setTimeout(function () {
            markValidation(emailInput, isEmail(emailInput.value));
        }, 1000);
    });

    confirmedPasswordInput.addEventListener('keyup', function () {
        setTimeout(function () {
            markValidation(confirmedPasswordInput, arePasswordsSame(passwordInput.value, confirmedPasswordInput.value));
        }, 1000);
    });
});
