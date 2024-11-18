class Ajax {
    constructor() {
    }

    createXHR(method, url, data, callback) {
        const xhr = new XMLHttpRequest();
        xhr.open(method, url, true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
                console.log('Raw response:', xhr.responseText); // Ajoutez cette ligne pour déboguer
                if (xhr.status === 200) {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        console.log('Response received:', response); // Ajoutez cette ligne pour voir la réponse
                        callback(response, null);
                    } catch (e) {
                        console.error('JSON parsing error:', e); // Log additional error details
                        callback(null, 'Invalid JSON response');
                    }
                } else {
                    callback(null, 'Erreur de connexion au serveur');
                }
            }
        };
        xhr.send(data);
    }
    
    

    genererChaineRequete(donnees) {
        const params = Object.entries(donnees)
            .map(([key, value]) => `${encodeURIComponent(key)}=${encodeURIComponent(value)}`)
            .join('&');
        return params;
    }

    resetMessages() {
        const messageIds = ['error', 'success'];
        messageIds.forEach(id => {
            const element = document.getElementById(id);
            if (element) {
                element.textContent = '';
            }
        });
    }
    

    traiterReponse(response, erreur, pagetype) {
        this.resetMessages();
        document.querySelectorAll('h5').forEach(p => p.textContent = '');
    
        if (erreur) {
            document.getElementById('error').textContent = erreur;
        } else if (response.success) {
            document.getElementById('success').textContent = response.message;
            if (response.redirect_url) {
                window.location.href = response.redirect_url;
            }
        } else {
            if (response.errors && Array.isArray(response.errors)) {
                response.errors.forEach(function(err) {
                    if (err.column && document.getElementById(err.column)) {
                        document.getElementById(err.column).textContent = err.message;
                    } else {
                        document.getElementById('error').textContent = err.message;
                    }
                });
            } else {
                document.getElementById('error').textContent = response.message;
            }
        }
    }
    
    
    
    
    

    envoyerFormulaireAuServeur(data, url, pagetype) {
        this.createXHR('POST', url, data, (response, erreur) => {
            if (erreur) {
                console.log('Erreur lors de l\'envoi de la requête:', erreur);
            } else {
                console.log('Réponse envoyée avec succès.');
                console.log('Page de la réponse:', url);
                this.traiterReponse(response, erreur, pagetype);
                this.handleFormResponse(response);
            }
        });
    }
    
    handleFormResponse(response) {
        // Vérifie et affiche ou cache les conteneurs de succès et d'alerte en fonction de la réponse
        const successContainer = document.querySelector('.success-container');
        const alertContainer = document.querySelector('.alert-container');
    
        if (successContainer) {
            if (response.success) {
                successContainer.style.display = 'flex';
                if (alertContainer) alertContainer.style.display = 'none';
            } else {
                if (alertContainer) alertContainer.style.display = 'none';
            }
        }
    
        if (alertContainer) {
            if (!response.success) {
                alertContainer.style.display = 'flex';
                if (successContainer) successContainer.style.display = 'none';
            }
        }
    }
    

    preparerDonnees(pagetype, config) {
        const formTypeConfig = config[pagetype];
        if (formTypeConfig) {
            const formData = new FormData();
            
            formTypeConfig.fields.forEach(field => {
                const element = document.getElementById(field);
                
                if (element) { // Assurez-vous que l'élément existe
                    if (element.type === 'file') {
                        if (element.files.length > 0) {
                            for (let i = 0; i < element.files.length; i++) {
                                formData.append(field, element.files[i]);
                            }
                        } else {
                            console.warn(`Aucun fichier sélectionné pour le champ ${field}.`);
                        }
                    } else {
                        formData.append(field, element.value);
                    }
                } else {
                    console.warn(`Élément avec l'ID ${field} introuvable.`);
                }
            });
    
            console.log("prepared donnee ", formData);
    
            this.envoyerFormulaireAuServeur(formData, formTypeConfig.url, pagetype);
        } else {
            console.error(`Unknown pagetype: ${pagetype}`);
        }
    }
    
    

    soumissionFormulaireGenerique(pagetype, config) {
        return function(event) {
            event.preventDefault();   
            this.preparerDonnees(pagetype, config);
        }.bind(this); // Bind pour lier le contexte de this à la fonction retournée
    }
}

export default Ajax;
