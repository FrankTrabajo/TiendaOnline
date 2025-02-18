document.addEventListener('DOMContentLoaded', function(){
    let form = document.getElementById('form');
    form.addEventListener('submit', async function(e){
        e.preventDefault();

        let name = document.getElementById('name');
        let email = document.getElementById('email');
        let password = document.getElementById('password');
        let password2 = document.getElementById('password2');

        fetch('/register', {
            method: 'POST',
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify({
                name, email, password, password2
            })
        })
    })
})