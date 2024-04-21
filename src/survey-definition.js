const surveyJson = {
  title: {
    default: "We're sad to see you go!",
    fr: "Nous sommes tristes de vous voir partir !",
    de: "Es tut uns leid, dass Sie gehen!",
    it: "Ci dispiace vederti partire!",
    es: "¡Nos entristece verte partir!"
  },
  description: {
    default: "By completing this survey, you're not only helping us improve, but you'll also gain access to exclusive promotional offers.",
    fr: "En complétant cette enquête, vous nous aidez non seulement à nous améliorer, mais vous aurez également accès à des offres promotionnelles exclusives.",
    de: "Indem Sie diese Umfrage ausfüllen, helfen Sie uns nicht nur zu verbessern, sondern erhalten auch Zugang zu exklusiven Werbeangeboten.",
    it: "Completando questo sondaggio, non solo ci aiuti a migliorare, ma otterrai anche accesso a offerte promozionali esclusive.",
    es: "Al completar esta encuesta, no solo nos ayudas a mejorar, sino que también obtendrás acceso a ofertas promocionales exclusivas."
  },
  showProgressBar: "top",
  pages: [
    {
      name: "unsubscribeReason",
      elements: [
        {
          type: "radiogroup",
          name: "reason",
          title: {
            default: "Why do you wish to unsubscribe from our service?",
            fr: "Pourquoi souhaitez-vous vous désabonner de notre service ?",
            de: "Warum möchten Sie unseren Service kündigen?",
            it: "Perché desideri disiscriverti dal nostro servizio?",
            es: "¿Por qué deseas darte de baja de nuestro servicio?"
          },
          isRequired: true,
          choices: [
            {
              value: "noLongerNeeded",
              text: {
                default: "I no longer need it",
                fr: "Je n'en ai plus besoin",
                de: "Ich benötige es nicht mehr",
                it: "Non ne ho più bisogno",
                es: "Ya no lo necesito"
              }
            },
            {
              value: "tooExpensive",
              text: {
                default: "It's too expensive",
                fr: "C'est trop cher",
                de: "Es ist zu teuer",
                it: "È troppo costoso",
                es: "Es demasiado caro"
              }
            },
            {
              value: "notSatisfied",
              text: {
                default: "I'm not satisfied with the service",
                fr: "Je ne suis pas satisfait du service",
                de: "Ich bin mit dem Service nicht zufrieden",
                it: "Non sono soddisfatto del servizio",
                es: "No estoy satisfecho con el servicio"
              }
            },
            {
              value: "other",
              text: {
                default: "Other",
                fr: "Autre",
                de: "Andere",
                it: "Altro",
                es: "Otro"
              }
            }
          ],
          colCount: 1
        },
        {
          type: "comment",
          name: "reasonDetail",
          visibleIf: "{reason} = 'other'",
          title: {
            default: "Please provide more details:",
            fr: "Veuillez fournir plus de détails :",
            de: "Bitte geben Sie weitere Details an:",
            it: "Si prega di fornire ulteriori dettagli:",
            es: "Por favor, proporcione más detalles:"
          },
          isRequired: true
        }
      ]
    },
    {
      name: "serviceQuality",
      elements: [
        {
          type: "rating",
          name: "serviceRating",
          title: {
            default: "How would you rate the quality of our service?",
            fr: "Comment évalueriez-vous la qualité de notre service ?",
            de: "Wie würden Sie die Qualität unseres Services bewerten?",
            it: "Come valuteresti la qualità del nostro servizio?",
            es: "¿Cómo calificarías la calidad de nuestro servicio?"
          },
          isRequired: true,
          minRateDescription: {
            default: "Very Poor",
            fr: "Très mauvais",
            de: "Sehr schlecht",
            it: "Molto scarso",
            es: "Muy malo"
          },
          maxRateDescription: {
            default: "Very Good",
            fr: "Très bon",
            de: "Sehr gut",
            it: "Molto buono",
            es: "Muy bueno"
          }
        }
      ],
      visibleIf: "{reason} = 'notSatisfied'"
    },
    // Continuer avec les autres éléments et pages...
  ],
  completedHtml: {
    default: "<h3>Thank you for your feedback!</h3><p>You will now be redirected to our exclusive promotional offers.</p><div class='loader'></div><small class='loading-text'>loading offers</small>",
    fr: "<h3>Merci pour vos retours !</h3><p>Vous allez maintenant être redirigé vers nos offres promotionnelles exclusives.</p><div class='loader'></div><small class='loading-text'>Chargement des offres</small>",
    de: "<h3>Danke für Ihr Feedback!</h3><p>Sie werden jetzt zu unseren exklusiven Werbeangeboten weitergeleitet.</p><div class='loader'></div><small class='loading-text'>Angebote werden geladen</small>",
    it: "<h3>Grazie per il tuo feedback!</h3><p>Ora verrai reindirizzato alle nostre offerte promozionali esclusive.</p><div class='loader'></div><small class='loading-text'>Caricamento offerte</small>",
    es: "<h3>¡Gracias por tus comentarios!</h3><p>Ahora serás redirigido a nuestras ofertas promocionales exclusivas.</p><div class='loader'></div><small class='loading-text'>Cargando ofertas</small>"
  }
};

export default surveyJson;
