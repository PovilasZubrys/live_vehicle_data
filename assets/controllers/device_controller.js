import { Controller } from '@hotwired/stimulus';

const myModal = document.getElementById('myModal')
const myInput = document.getElementById('myInput')

/* stimulusFetch: 'lazy' */
export default class extends Controller {
    connect() {
        myModal.addEventListener('shown.bs.modal', () => {
            myInput.focus()
        })
    }

    disconnect() {
        myModal.removeEventListener('shown.bs.modal', () => {
            myInput.focus()
        })
    }
}