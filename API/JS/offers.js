// Fonction pour récupérer et afficher les offres d'emploi
function getJobAds() {
    fetch('http://localhost:8000/job_ads')
        .then(response => response.json())
        .then(data => {
            const jobAdsContainer = document.getElementById('job-ads-container');
            jobAdsContainer.innerHTML = ''; // Vider le conteneur

            data.job_ads.forEach(ad => {
                const adElement = document.createElement('div');
                adElement.innerHTML = `
                    <h1>${ad.job_title}</h1>
                    <h2>${ad.company_name}</h2>
                    <h3>${ad.city}</h3>
                    <h4>${ad.contract_type}</h4>
                    <h5>${ad.wage}</h5>
                    <p>${ad.description}</p>
                `;
                jobAdsContainer.appendChild(adElement);
            });
        })
        .catch(error => console.error('Erreur:', error));
}

// Appeler la fonction au chargement de la page
document.addEventListener('DOMContentLoaded', getJobAds);