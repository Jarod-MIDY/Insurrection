import { Controller } from '@hotwired/stimulus';

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
    static values = {
        mercureUrl: String,
        mercureEventFlag: String
    }
    connect() {
        this.eventSource = new EventSource(this.mercureUrlValue);
        this.eventSource.onmessage = event => {
            this.dispatch(this.mercureEventFlagValue, {
                bubbles: true,
                detail: JSON.parse(event.data)
            });
        }
    }
}
