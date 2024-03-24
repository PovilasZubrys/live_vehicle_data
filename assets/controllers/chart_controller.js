import { Controller } from '@hotwired/stimulus';

let mercureEventSource = null;

export default class extends Controller {
    connect() {
        this.element.addEventListener('chartjs:connect', this._onConnect);
        mercureEventSource = new EventSource(JSON.parse(document.getElementById('mercure-url').textContent))
    }

    disconnect() {
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
                if (dataType === 'engine_load') {
                    data[dataType] = data[dataType].toFixed(2)
                }
                addData(event.detail.chart, data[dataType])
                removeData(event.detail.chart)
            }
        }
    }
}