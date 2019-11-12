function notification(message, type) {
  Vue.toasted.show(message, {
    type: type,
    theme: "bubble",
    position: "top-center",
    duration: 5000
  });
}
export {notification};