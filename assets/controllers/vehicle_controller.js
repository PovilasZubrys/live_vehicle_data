import { Controller } from '@hotwired/stimulus';

let mercureEventSource = null;

export default class extends Controller {
    connect() {
        this.element.addEventListener('chartjs:connect', this._onConnect);
        mercureEventSource = new EventSource(JSON.parse(document.getElementById('mercure-url').textContent))
    }

    disconnect() {
        // You should always remove listeners when the controller is disconnected to avoid side effects
        this.element.removeEventListener('chartjs:connect', this._onConnect);
        mercureEventSource && mercureEventSource.close()
        mercureEventSource = null
    }

    _onConnect(event) {
        let dataType= event.target.dataset.dataType

        function addData(chart, newData) {
            chart.data.labels.push(newData);
            chart.data.datasets.forEach((dataset) => {
                dataset.data.push(newData);
            });
            chart.update();
        }

        function updateData(data, dataType) {
            let element = document.getElementById(dataType)
            if (element) {
                document.getElementById(dataType).innerHTML = data[dataType]
            }
        }

        function removeData(chart) {
            chart.data.labels.shift();
            chart.data.datasets.forEach((dataset) => {
                dataset.data.shift();
            });
            chart.update();
        }

        mercureEventSource.onmessage = (mercureEvent) => {
            if (mercureEventSource == null) {
                return;
            }

            let data = JSON.parse(mercureEvent.data)
            if (data[dataType]) {
                addData(event.detail.chart, data[dataType])
                updateData(data, dataType)
                removeData(event.detail.chart)
            }
        }
    }
}