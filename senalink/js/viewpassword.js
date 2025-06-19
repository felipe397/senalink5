document.addEventListener("DOMContentLoaded", function () {
  const passwordInput = document.querySelector('input[name="contrasena"]');
  const toggleButton = document.querySelector(".box__password button");
  const eyeIcon = document.getElementById("viewPassword");

  toggleButton.addEventListener("click", function (e) {
    e.preventDefault(); // evita que el botón haga submit

    const isPassword = passwordInput.type === "password";
    passwordInput.type = isPassword ? "text" : "password";

    // Opcional: cambia el ícono según el estado
    eyeIcon.src = isPassword 
      ? "../../../img/icons8-eye-24.png" // imagen de ojo abierto
      : "../../../img/icons8-invisible-24.png";      // imagen de ojo cerrado
  });
});
