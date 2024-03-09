import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    connect() {
        this.element.addEventListener('chartjs:connect', this._onConnect);
    }

    disconnect() {
        // You should always remove listeners when the controller is disconnected to avoid side effects
        this.element.removeEventListener('chartjs:connect', this._onConnect);
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
            document.getElementById(dataType).innerHTML = data[dataType]
        }

        function removeData(chart) {
            chart.data.labels.shift();
            chart.data.datasets.forEach((dataset) => {
                dataset.data.shift();
            });
            chart.update();
        }

        var element = document.getElementById(dataType).innerHTML;
        const url = JSON.parse(document.getElementById('mercure-url').textContent)
        const eventSource = new EventSource(url)
        eventSource.onmessage = (mercureEvent) => {
            let data = JSON.parse(mercureEvent.data)
            addData(event.detail.chart, data[dataType])
            updateData(data, dataType)
            removeData(event.detail.chart)
        }
    }
}