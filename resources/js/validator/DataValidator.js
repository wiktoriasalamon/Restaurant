import validator from 'validator';

function isEmail(email) {
    return validator.isEmail(email)
}

function isPassword(password) {
    if (password.length<5) return false
    else return true
}

function isPhoneNumber(phoneNumber) {
    return validator.isMobilePhone(phoneNumber,'pl-PL')
}

function isPostalCode(postalCode) {
    return validator.isPostalCode(postalCode, 'PL')
}


export {isEmail, isPassword, isPhoneNumber, isPostalCode};