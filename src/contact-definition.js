const contactFormJson = {
    title: {
        default: "Contact us",
        fr: "Contactez-nous",
        de: "Kontaktieren Sie uns",
        it: "Contattaci",
        es: "Contáctenos"
    },
    showProgressBar: "off",
    pages: [
        {
            name: "contactPage",
            elements: [
                {
                    type: "text",
                    name: "name",
                    title: {
                        default: "Your name",
                        fr: "Votre nom",
                        de: "Ihr Name",
                        it: "Il tuo nome",
                        es: "Tu nombre"
                    },
                    isRequired: true
                },
                {
                    type: "text",
                    name: "email",
                    title: {
                        default: "Your email",
                        fr: "Votre email",
                        de: "Ihre E-Mail",
                        it: "La tua email",
                        es: "Tu correo electrónico"
                    },
                    isRequired: true,
                    validators: [
                        {
                            type: "email"
                        }
                    ]
                },
                {
                    type: "text",
                    name: "subject",
                    title: {
                        default: "Subject",
                        fr: "Sujet",
                        de: "Betreff",
                        it: "Soggetto",
                        es: "Asunto"
                    },
                    isRequired: true
                },
                {
                    type: "comment",
                    name: "message",
                    title: {
                        default: "Message",
                        fr: "Message",
                        de: "Nachricht",
                        it: "Messaggio",
                        es: "Mensaje"
                    },
                    isRequired: true
                }
            ]
        }
    ],
    completedHtml: {
        default: "<h3>Thank you for contacting us. We will get back to you as soon as possible.</h3>",
        fr: "<h3>Merci de nous avoir contacté. Nous vous répondrons dès que possible.</h3>",
        de: "<h3>Danke, dass Sie uns kontaktiert haben. Wir werden uns so schnell wie möglich bei Ihnen melden.</h3>",
        it: "<h3>Grazie per averci contattato. Ti risponderemo il prima possibile.</h3>",
        es: "<h3>Gracias por contactarnos. Nos pondremos en contacto contigo lo antes posible.</h3>"
    }
};
