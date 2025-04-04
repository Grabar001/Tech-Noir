import { Controller } from '@hotwired/stimulus'

export default class extends Controller {
    static targets = ['filtreSelect', 'valeursSelect']

    loadValeurs(event) {
        const filtreId = event.target.value
        const url = `/admin/ajax/valeurs-par-filtre/${filtreId}`

        fetch(url)
            .then(response => response.json())
            .then(data => {
                this.valeursSelectTarget.innerHTML = ''

                data.forEach(valeur => {
                    const option = document.createElement('option')
                    option.value = valeur.id
                    option.text = valeur.valeur
                    this.valeursSelectTarget.appendChild(option)
                })
            })
    }
}