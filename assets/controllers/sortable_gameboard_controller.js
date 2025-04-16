import { Controller } from '@hotwired/stimulus'
import Sortable from 'sortablejs'
export default class extends Controller {
    static targets = ['board', 'characterList', 'character']
    connect() {
        if (this.hasBoardTarget && this.hasCharacterListTarget) {
            this.createBoard()
            this.createcharacterList()
        } else {
            console.log('no targets');
        }
    }

    boardTargetConnected() {
        this.createBoard()
    }

    characterListTargetConnected() {
        this.createcharacterList()
    }

    createBoard() {
        console.log('board connected');
        new Sortable(this.boardTarget, {
            group: 'board',
            filter: '.static',
            onAdd: (event) => {
                this.updateCharacterPresence(event.item.dataset.route, '1')
            }
        })
    }

    createcharacterList() {
        new Sortable(this.characterListTarget, {
            group: 'board',
            filter: '.static',
            onAdd: (event) => {
                this.updateCharacterPresence(event.item.dataset.route, '0')
                if (event.to.querySelectorAll('li').length > 0 && event.to.querySelectorAll('#noCharacters').length > 0) {
                    event.to.querySelectorAll('#noCharacters')[0].remove()
                }
            },
            onRemove: (event) => {
                if (event.from.querySelectorAll('li').length === 0) {
                    const span = document.createElement('span')
                    span.setAttribute('id', 'noCharacters')
                    span.innerText = "Vous n'avez pas de personnages à ajouter à la scène"
                    event.from.appendChild(span)
                }
            }
        })
    }

    updateCharacterPresence(path, isPresent) {
        const regex = /^\/scene\/[^\/]+\/character\/[^\/]+\/?/;
        if (regex.test(path)) {
            // Remove anything after the last valid slash
            const match = path.match(regex);
            path = match ? match[0].replace(/\/?$/, '/') : null;
        } else {
            path = null;
        }
        if (path) {
            fetch(path + isPresent, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
        }
    }
}
