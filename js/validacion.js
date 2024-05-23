document.addEventListener("DOMContentLoaded", function() {
    const formulario = document.getElementById("formInicioSesion");

    formulario.addEventListener("submit", function(event) {
        event.preventDefault();   //evita el envio del formulario temporalmente

        //limpiar mensajes de error previos
        document.querySelectorAll('.error-message').forEach(function(error) {
            error.remove();
        });

        /* const email = document.getElementById("email").value.trim(); */
        const emailRegEx = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        const email = emailRegEx.test(document.getElementById("email").value);
        const password = document.getElementById("password").value.trim();
        let valid = true;

        if(!email) {
            showError("email", "El campo de email es obligatorio");
            valid = false;
        }

        if(!password) {
            showError("password", "El campo de contraseña es obligatorio");
            valid = false;
        }

        if(valid) {
            formulario.submit();   //si todo esta correcto se envia el form
        }
    });

    function showError(inputId, message) {
        const inputElement = document.getElementById(inputId);
        const errorElement = document.createElement("div");
        inputElement.style.marginBottom = "0.4rem";
        inputElement.style.marginTop = "0.4rem"; 
        errorElement.className = "error-message";
        errorElement.innerText = message;
        inputElement.parentNode.appendChild(errorElement);
    }
});