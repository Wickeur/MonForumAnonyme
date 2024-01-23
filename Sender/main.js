import './style.css'
import javascriptLogo from './javascript.svg'
import viteLogo from '/vite.svg'
import { setupCounter } from './counter.js'

// document.querySelector('#app').innerHTML = `
//   <div>
//     <a href="https://vitejs.dev" target="_blank">
//       <img src="${viteLogo}" class="logo" alt="Vite logo" />
//     </a>
//     <a href="https://developer.mozilla.org/en-US/docs/Web/JavaScript" target="_blank">
//       <img src="${javascriptLogo}" class="logo vanilla" alt="JavaScript logo" />
//     </a>
//     <h1>Hello Vite!</h1>
//     <div class="card">
//       <button id="counter" type="button"></button>
//     </div>
//     <p class="read-the-docs">
//       Click on the Vite logo to learn more
//     </p>
//   </div>
// `

// Fonction pour ajouter une classe
function addClass() {
  var messageUser = document.getElementById('messageUser').value;
  var messageDescription = document.getElementById('messageDescription').value;

  // Exemple avec jQuery
  $.ajax({
      url: 'http://localhost:3000/Sender',
      type: 'POST',
      contentType: 'application/json',
      data: JSON.stringify({ user: messageUser, description: messageDescription }),
      success: function (response) {
          // Redirigez l'utilisateur vers la page de la liste des classes après la mise à jour
          window.location.href = 'http://localhost:8000';
      },
      error: function (error) {
          console.error('Erreur lors de l\'ajout du message:', error);
      }
  });
}

setupCounter(document.querySelector('#counter'))

