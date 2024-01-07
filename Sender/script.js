// Fonction pour ajouter une classe
function addClass() {
    var messageUser = document.getElementById('messageUser').value;
    var messageDescription = document.getElementById('messageDescription').value;

    // Exemple avec jQuery
    $.ajax({
        url: 'http://localhost:3000/Sender',
        type: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({ name: messageUser, level: messageDescription }),
        success: function (response) {
            // Redirigez l'utilisateur vers la page de la liste des classes après la mise à jour
            window.location.href = 'localhost:8000';
        },
        error: function (error) {
            console.error('Erreur lors de l\'ajout de la classe:', error);
        }
    });
}
