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
    connect() {
        this.element.addEventListener('chartjs:connect', this._onConnect);
    }

    disconnect() {
        // You should always remove listeners when the controller is disconnected to avoid side effects
        this.element.removeEventListener('chartjs:connect', this._onConnect);
    }

    _onConnect(event) {
        function addData(chart, label, newData) {
            chart.data.labels.push(label);
            chart.data.datasets.forEach((dataset) => {
                dataset.data.push(newData);
            });
            chart.update();
        }

        function removeData(chart) {
            chart.data.labels.shift();
            chart.data.datasets.forEach((dataset) => {
                dataset.data.shift();
            });
            chart.update();
        }

        function getSpeed()
        {
            let xhr = new XMLHttpRequest();

            // Making our connection
            let url = window.location.href + 'getSpeed';
            xhr.open("GET", url, true);

            // function execute after request is successful
            xhr.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    addData(event.detail.chart, this.responseText, this.responseText)
                    removeData(event.detail.chart)
                }
            }

            xhr.send();

            setTimeout(getSpeed, 2000)
        }

        getSpeed()
    }
}
