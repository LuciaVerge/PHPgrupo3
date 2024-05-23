document.addEventListener("DOMContentLoaded", function() {
    const formulario = document.getElementById("formInicioSesion");

    formulario.addEventListener("submit", function(event) {
        event.preventDefault();   //evita el envio del formulario temporalmente

//selecciona todo elementos clase .error-message y los elimina del DOM evitando que se acumulen mensajes de error
        document.querySelectorAll('.error-message').forEach(function(error) {
            error.remove();
        });

        const emailRegEx = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;   //expresion regular para controlar el formato de un email
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

/**
 * Crea elemento div para contener mensaje de error
 * 
 * @param {string} inputId ID del input que tiene el error
 * @param {string} message Mensaje de error 
 */
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