window.onload = () => {

    const FilterForm = document.querySelector('#filters')

    // Rajouter un evemenent sur chaque changement de chaque input dans le filtre
    document.querySelectorAll('#filters input').forEach(input => {
        input.addEventListener('change', () => {

            // Récuperer chacune des données du formulaire pour faire des filtres
            const Form = new FormData(FilterForm);

            // Contruire la "queryString" 
            const Params = new URLSearchParams();

            Form.forEach((value, key) => {
                Params.append(key, value)
            })

            // Récuperer l'url sur lequel on se trouve
            url = new URL(window.location.href);

            // Lancer la requete ajax avec l'url et le query string
            fetch(url.pathname + '?' + Params.toString() + '&ajax=1', {
                headers: {
                    "X-Requester-With": "XMLHttpRequest"
                }
            }).then(
                response => response.json()
            ).then(data => {
                // On va chercher la zone de contenu
                const content = document.querySelector("#content");

                // On remplace le contenu
                content.innerHTML = data.content;

                history.pushState({}, "", url.pathname + "?" + Params.toString());

                // On met à jour l'url)
            }).catch(e => alert(e));
        });
    })
}
