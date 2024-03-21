import { Controller } from '@hotwired/stimulus';

let mercureEventSource = null;

export default class extends Controller {
    connect() {
        mercureEventSource = new EventSource(JSON.parse(document.getElementById('mercure-url').textContent))

        this.updateData(mercureEventSource)
    }

    disconnect() {
        mercureEventSource && mercureEventSource.close()
        mercureEventSource = null
    }

    updateData(mercureEventSource) {

        function update(data)
        {
            let dataType = Object.keys(data)[0]
            let element = document.getElementById(dataType)

            if (dataType == 'gps') {
                data[dataType] = 'Latitude: ' + data[dataType]['latitude'] + ' longitude: ' + data[dataType]['longitude']
            }

            if (element != null) {
                document.getElementById(dataType).innerHTML = data[dataType]
            }
        }

        mercureEventSource.onmessage = (mercureEvent) => {
            if (mercureEventSource == null) {
                return;
            }

            let data = JSON.parse(mercureEvent.data)
            update(data)
        }
    }
}