import { Controller } from '@hotwired/stimulus';
import * as Turbo from '@hotwired/turbo'
/*
 * This is an example Stimulus controller!
 *
 * Any element with a data-controller="hello" attribute will cause
 * this controller to be executed. The name "hello" comes from the filename:
 * hello_controller.js -> "hello"
 *
 * Delete this file or adapt it for your use!
 */
export default class extends Controller {
    static targets = ['frame']
    static values = {
        mercureUrl: String,
        mercureEventFlag: String
    }
    connect() {
        this.eventSource = new EventSource(this.mercureUrlValue);
        this.eventSource.onmessage = event => {
            console.log('event mercure-listen received');
            if (this.hasFrameTarget) {
                this.frameTargets.forEach(element => {
                    Turbo.visit(element.src, { frame: element.id })
                });
            }
            this.dispatch(this.mercureEventFlagValue, {
                bubbles: true,
                detail: JSON.parse(event.data)
            });
        }
    }
}
