import 'jquery';
import * as Survey from 'survey-jquery';
import surveyJson from './survey-definition';
import 'survey-jquery/modern.css'; // Pour le thème "modern"
import 'bootstrap/dist/js/bootstrap.bundle';
import '../sass/styles.scss';

$(document).ready(function() {
  // Appliquez le thème par défaut
  Survey.StylesManager.applyTheme("default");

  // Initialisez le sondage avec la configuration JSON et définissez la langue par défaut sur le français
  const survey = new Survey.Model(surveyJson);
  survey.locale = surveyJsLocale; // Utilisez la locale passée depuis le backend

  // Gestion de la complétion du sondage
  survey.onComplete.add(function (result) {
    // Préparez le corps de la requête basé sur les résultats du sondage
    const surveyData = result.data;
    const body = {
      "fields": {
        "Reason": surveyData.reason, // Assurez-vous que ce champ correspond à un champ de votre table Airtable
        "Reason Detail": surveyData.reasonDetail || '', // Utilisez || '' pour gérer les cas où la réponse est facultative
        "Service Rating": surveyData.serviceRating || '', // De même, assurez-vous que les noms correspondent
        // Ajoutez d'autres champs au besoin, en fonction des réponses du sondage
      }
    };

    const airtableUrl = "https://api.airtable.com/v0/appLyzSeYkHqhC1wy/upsellifty";
    const authToken = "patA2jGawyrYUehDL.6cb784c3b2a77c2c4130bf67befbb6ea4042c393871106133ffdb6700461b414"; // Remplacez par votre token d'authentification
    const headers = new Headers({
        'Authorization': `Bearer ${authToken}`,
        'Content-Type': 'application/json'
    });

    // Effectuez la requête POST à Airtable
    fetch(airtableUrl, {
      method: 'POST',
      headers: headers,
      body: JSON.stringify(body)
    })
    .then(response => response.json())
    .then(data => {
      // Affichez la seconde modale avec les offres promotionnelles après un délai
      setTimeout(function() {
          $('#modalForm').modal('hide');
          $('#modalOffers').modal('show');
          $('.loader').remove();
      }, 4000); // Le délai est de 3000 millisecondes (3 secondes)
    })
    .catch((error) => {
      console.error('Error:', error);
      // Gérez l'erreur (peut-être réessayer ou afficher un message à l'utilisateur)
    });
  });

  // Affichez le sondage dans l'élément avec l'id "surveyElement"
  $("#surveyElement").Survey({
    model: survey,
    onComplete: function(result) {
      // Logique de complétion du sondage
    }
  });
});
