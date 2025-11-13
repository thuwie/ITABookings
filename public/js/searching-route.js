const RouteSearch = {
    init() {
        this.disableSearchingBtnAsRouteUnselect();
        this.addEventElements();
    },

    disableSearchingBtnAsRouteUnselect() {
        const fromLocationSelect = document.querySelector('#from_location');
        const toLocationSelect = document.querySelector('#to_location');
        const searchingBtn = document.querySelector("#searching-btn");
        if (fromLocationSelect.value === "" && toLocationSelect.value === "") {
            searchingBtn.disabled = true;
        };
    },

    addEventElements() {
        const fromLocationSelect = document.querySelector('#from_location');
        const toLocationSelect = document.querySelector('#to_location');
        const searchingBtn = document.querySelector("#searching-btn");
        const form = document.querySelector("#searching-form");
        let from;
        let to;
        fromLocationSelect.addEventListener('change', () => {
            if (toLocationSelect.value != "" && fromLocationSelect.value != "" &&
                toLocationSelect.value !== fromLocationSelect.value) {
                searchingBtn.disabled = false;
                searchingBtn.innerHTML = "Tìm kiếm";
                searchingBtn.classList.toggle('searching-btn-29-10');
                searchingBtn.classList.toggle('border-dark');
                from = fromLocationSelect.value;
                to = toLocationSelect.value;
            } else {
                searchingBtn.disabled = true;
                searchingBtn.innerHTML = "Vui lòng chọn địa điểm";
                searchingBtn.classList.remove('searching-btn-29-10');
                searchingBtn.classList.remove('border-dark');
            };
        })

        toLocationSelect.addEventListener('change', () => {
            if (fromLocationSelect.value != "" && toLocationSelect.value != ""
                &&
                toLocationSelect.value !== fromLocationSelect.value
            ) {
                searchingBtn.disabled = false;
                searchingBtn.innerHTML = "Tìm kiếm";
                searchingBtn.classList.toggle('searching-btn-29-10');
                searchingBtn.classList.toggle('border-dark');
                from = fromLocationSelect.value;
                to = toLocationSelect.value;
            } else {
                searchingBtn.disabled = true;
                searchingBtn.innerHTML = "Vui lòng chọn địa điểm";
                searchingBtn.classList.remove('searching-btn-29-10');
                searchingBtn.classList.remove('border-dark');
            };
        })

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const res = await fetch(`/routes?from=${from}&to=${to}`);
            const routes = await res.json();
            const { data } = routes;
            this.renderRoutes(data);
            window.location.href = `/searching-routes?from=${from}&to=${to}`;
        })

    },

    renderRoutes(data) {
        const container = document.getElementById('table-data');
        container.innerHTML = ''; // clear old content

        if (!data || data.length === 0) {
            container.innerHTML = '<p>Không tìm thấy tuyến đường nào.</p>';
            return;
        }

        // Create table element
        const table = document.createElement('table');
        table.className = 'table table-striped';

        // Create thead
        const thead = document.createElement('thead');
        thead.innerHTML = `
        <tr>
            <th scope="col">#</th>
            <th scope="col">First</th>
            <th scope="col">Last</th>
            <th scope="col">Handle</th>
        </tr>
    `;
        table.appendChild(thead);

        // Create tbody
        const tbody = document.createElement('tbody');

        data.forEach((item, index) => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
            <th scope="row">${index + 1}</th>
            <td>${item.first}</td>
            <td>${item.last}</td>
            <td>${item.handle}</td>
        `;
            tbody.appendChild(tr);
        });

        table.appendChild(tbody);
        container.appendChild(table);
    }

};

document.addEventListener('DOMContentLoaded', () => RouteSearch.init());
