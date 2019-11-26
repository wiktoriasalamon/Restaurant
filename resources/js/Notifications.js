function notification(message, type) {
  Vue.toasted.show(message, {
    type: type,
    theme: "bubble",
    position: "top-center",
    duration: 5000
  });
}

// Helpery do notifications
function notificationError(message) {
  notification(message, 'error');
}

function notificationSuccess(message) {
  notification(message, 'success');
}


export {notification, notificationError, notificationSuccess};