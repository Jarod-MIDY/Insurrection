import { Controller } from '@hotwired/stimulus'
import Sortable from 'sortablejs'
export default class extends Controller {
    static targets = ['board', 'characterList', 'character']
    connect() {
        if (this.hasBoardTarget && this.hasCharacterListTarget) {
            new Sortable(this.boardTarget, {
                group: 'board',
            })
            new Sortable(this.characterListTarget, {
                group: 'board',
            })
        } else {
            console.log('no targets');
        }
    }

    boardTargetConnected() {
        console.log('board connected');
        new Sortable(this.boardTarget, {
            group: 'board',
        })
    }

    characterListTargetConnected() {
        console.log('character list connected');
        new Sortable(this.characterListTarget, {
            group: 'board',
        })
    }
}
