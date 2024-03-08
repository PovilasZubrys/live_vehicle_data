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
        let vehicleId = event.target.dataset.vehicleId
        let dataType= event.target.dataset.dataType

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

        function updateData(data, dataType) {
            document.getElementById(dataType).innerHTML = data;
        }

        function getData()
        {
            let xhr = new XMLHttpRequest();
            let url = 'http://' + window.location.host + '/get_vehicle_data/' + dataType + '/' + vehicleId;
            xhr.open("GET", url, true);
            xhr.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    addData(event.detail.chart, this.responseText, this.responseText)
                    removeData(event.detail.chart)
                    updateData(this.responseText, dataType)
                }
            }

            xhr.send();

            setTimeout(getData, 1000)
        }

        getData(vehicleId, dataType)
    }
}