document.addEventListener("DOMContentLoaded", function() {
    document.getElementById("form").addEventListener('submit', validarFormulario); 
  });

function validarFormulario(evento){
    evento.preventDefault();

    var errores = []

    var name = document.getElementById('name').value;
    var email = document.getElementById('email').value;
    var password = document.getElementById('password').value;
    var password_confirmation = document.getElementById('password_confirmation').value;


    switch(true){
        case name.length == 0:
            errores.push('The field name is required');
            break; 
        case name.length < 3:
            errores.push('The name must have a minimum of 3 characters');
            break;
        case name.length > 20:
            errores.push('The name must have a maximum of 20 characters');
            break;
    }

    re=/^([\da-z_\.-]+)@([\da-z\.-]+)\.([a-z\.]{2,6})$/;
    switch(true){
        case email.length == 0:
            errores.push('The field email is required');
            break; 
        case !re.exec(email):
            errores.push('Invalid email address');
            break;
    }

    switch(true){
        case password.length == 0:
            errores.push('The field password is required');
            break; 
        case password.length < 8:
            errores.push('The password must have a minimum of 8 characters');
            break;
        case password.length > 16:
            errores.push('The password must have a maximum of 16 characters');
            break;     
        case password != password_confirmation:
            errores.push('Passwords do not match');
            break;
    }

    if(errores.length > 0){
        buildErrorsBlock(errores);
        return
    }
    this.submit();
}


function buildErrorsBlock(errores){
    const container = document.getElementById('messages');
    container.innerHTML = '';
    container.setAttribute('class','errors');

    errores.forEach(element => {
        const p = document.createElement("p");
        p.setAttribute('class','margin-3');
        p.textContent = element;
        container.appendChild(p);
    });
    
    container.setAttribute('class','errors');
    container.insertAdjacentElement("afterbegin",div);

}