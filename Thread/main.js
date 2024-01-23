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

// Fonction pour récupérer la liste des messages depuis l'API
function getMessageList() {
  $.ajax({
      url: 'http://localhost:3000/Thread',
      type: 'GET',
      success: function (response) {
          // Appel à la fonction pour afficher la liste des messages
          displayMessageList(response);
      },
      error: function (error) {
          console.error('Erreur lors de la récupération de la liste des messages:', error);
      }
  });
}

// Fonction pour afficher la liste des messages en format tableau
function displayMessageList(messages) {
  var messagesTable = document.getElementById('messagesTable');
  messagesTable.innerHTML = '';

  // Création de l'en-tête du tableau
  var headerRow = document.createElement('tr');
  headerRow.innerHTML = '<th scope="col">Nom utilisateur</th><th scope="col">Contenu</th>';
  messagesTable.appendChild(headerRow);

  // Parcours des messages
  for (var messageId in messages) {
      if (messages.hasOwnProperty(messageId)) {
          var classItem = messages[messageId];

          // Création d'une ligne pour chaque classe
          var row = document.createElement('tr');
          row.innerHTML = '<td>' + classItem.user + '</td>' +
              '<td>' + classItem.description + '</td>';

          messagesTable.appendChild(row);
      }
  }
}

getMessageList();

setupCounter(document.querySelector('#counter'))
