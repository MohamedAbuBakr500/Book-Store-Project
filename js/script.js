document.addEventListener('DOMContentLoaded', () => {
    const userBox = document.querySelector('.header .header-2 .user-box');
    const userBtn = document.querySelector('#user-btn');

    if (userBtn && userBox) {
        userBtn.addEventListener('click', () => {
            userBox.classList.toggle('active');
            navbar.classList.remove('active');
            // console.log('User box visibility toggled.');
        });
    } else {
        console.error('Element not found: #user-btn or .user-box');
    }
});


let navbar = document.querySelector('.header .header-2 .navbar');

document.querySelector('#menu-btn').onclick = () =>{
   navbar.classList.toggle('active');
   userBox.classList.remove('active');
}


window.onscroll = () =>{
    userBox.classList.remove('active');
    navbar.classList.remove('active');
 
    if(window.scrollY > 60){
       document.querySelector('.header .header-2').classList.add('active');
    }else{
       document.querySelector('.header .header-2').classList.remove('active');
    }
 }

 