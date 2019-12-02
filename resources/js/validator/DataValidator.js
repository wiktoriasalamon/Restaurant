import validator from 'validator';

function isEmail(email) {
    return validator.isEmail(email)
}

function isPassword(password) {
    if (password.length<6) return false
    else return true
}

function isPhoneNumber(phoneNumber) {
    return validator.isMobilePhone(phoneNumber,'pl-PL')
}

function passwordMatch(password1, password2) {
    return (password1.localeCompare(password2) === 0)
}
function isPostalCode(code) {
    return /^\d{2}-\d{3}$/.test(code)
}

export {isEmail, isPassword, isPhoneNumber, passwordMatch, isPostalCode};