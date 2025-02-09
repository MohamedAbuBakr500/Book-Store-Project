let navbar = document.querySelector('.header .navbar');
let accountBox = document.querySelector('.header .account-box');

document.querySelector('#menu-btn').onclick = () =>{
   navbar.classList.toggle('active');
   accountBox.classList.remove('active');
}

document.querySelector('#user-btn').onclick = () =>{
   accountBox.classList.toggle('active');
   navbar.classList.remove('active');
}

window.onscroll = () =>{
   navbar.classList.remove('active');
   accountBox.classList.remove('active');
}

document.querySelector('#close-update').onclick = () =>{
   document.querySelector('.edit-product-form').style.display = 'none';
   window.location.href = 'admin_products.php';
}

// Function to show a popup message
function showPopupMessage(message) {
   // Create the popup container dynamically
   const popup = document.createElement('div');
   popup.className = 'popup-message';
   popup.textContent = message;

   // Append to the body
   document.body.appendChild(popup);

   // Add the "show" class to make it visible
   setTimeout(() => {
      popup.classList.add('show');
   }, 0);

   // Remove the popup after 2 seconds
   setTimeout(() => {
      popup.classList.remove('show');
      // Remove from DOM after transition
      setTimeout(() => {
         popup.remove();
      }, 300);
   }, 2000);
}
