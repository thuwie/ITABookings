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
        const seatFilter = document.querySelector("#seats");
        const providerFilter = document.querySelector("#providers");
        const userId = document.getElementById('user_id').value;
        let from;
        let to;

        seatFilter.addEventListener('click', async (e) => {
            if (e.target && e.target.classList.contains('seat')) {
                const selectedProviderRadio = providerFilter.querySelector('input[type="radio"]:checked');
                let id = null;

                if (selectedProviderRadio) {
                    id = selectedProviderRadio.value;
                };

                const seat = e.target.value;
                RouteSearch.showLoading();
                const res = await fetch(`/routes?from=${from}&to=${to}&seat_counting=${seat}&provider=${id}`);
                const routes = await res.json();
                const { data } = routes;
                this.renderRoutes(data, userId);
                RouteSearch.hideLoading();
            }
        });

        providerFilter.addEventListener('click', async (e) => {
            if (e.target && e.target.classList.contains('provider')) {
                const id = e.target.value;
                const selectedSeatRadio = seatFilter.querySelector('input[type="radio"]:checked');
                let seat = null;

                if (selectedSeatRadio) {
                    seat = selectedSeatRadio.value;
                };

                RouteSearch.showLoading();
                const res = await fetch(`/routes?from=${from}&to=${to}&provider=${id}&seat_counting=${seat}`);
                const routes = await res.json();
                const { data } = routes;
                this.renderRoutes(data, userId);
                RouteSearch.hideLoading();
            }
        });

        fromLocationSelect.addEventListener('change', () => {
            if (toLocationSelect.value != "" && fromLocationSelect.value != "" &&
                toLocationSelect.value !== fromLocationSelect.value) {
                searchingBtn.disabled = false;
                searchingBtn.classList.toggle('searching-btn-29-10');
                searchingBtn.classList.toggle('border-dark');
                from = fromLocationSelect.value;
                to = toLocationSelect.value;
            } else {
                searchingBtn.disabled = true;
                searchingBtn.classList.remove('searching-btn-29-10');
                searchingBtn.classList.remove('border-dark');
            };
        });

        toLocationSelect.addEventListener('change', () => {
            if (fromLocationSelect.value != "" && toLocationSelect.value != ""
                &&
                toLocationSelect.value !== fromLocationSelect.value
            ) {
                searchingBtn.disabled = false;
                searchingBtn.classList.toggle('searching-btn-29-10');
                searchingBtn.classList.toggle('border-dark');
                from = fromLocationSelect.value;
                to = toLocationSelect.value;
            } else {
                searchingBtn.disabled = true;
                searchingBtn.classList.remove('searching-btn-29-10');
                searchingBtn.classList.remove('border-dark');
            };
        });

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            RouteSearch.showLoading();
            const res = await fetch(`/routes?from=${from}&to=${to}`);
            const routes = await res.json();
            const { data } = routes;
            this.renderRoutes(data, userId);
            RouteSearch.hideLoading();
        })

    },

    showLoading() {
        const loadingOverlay = document.getElementById('loadingOverlay');
        loadingOverlay.style.display = 'flex';
    },
    hideLoading() {
        const loadingOverlay = document.getElementById('loadingOverlay');
        loadingOverlay.style.display = 'none';
    },
    renderRoutes(data, userId) {
        const { result, route } = data;
        const textHeading = document.getElementById('heading-place');
        const content = `Xe đi từ ${route.from} đến ${route.to} theo đường ${route.route_name}`;
        textHeading.innerText = content;

        const filter = document.getElementById('filter');
        filter.style.display = "block";
        const container = document.querySelector('#routesContainer'); // Add a container div in your HTML
        container.innerHTML = ''; // Clear previous results

        result.forEach(providerBlock => {
            providerBlock.vehicles.forEach(vehicle => {
                const html = `
            <div class="border rounded-1 p-2" style="border-color: #D7D7D7">
                <div class="row">
                    <div class="col-2">
                        <img src="${providerBlock.provider.logo}" 
                             style="width: 100%; height: 140px; border-radius: 2px;" alt="">
                    </div>
                    <div class="col-7">
                        <div class="d-flex flex-column gap-2">
                            <div class="d-flex gap-2">
                                <span class="fw-medium">Nhà cung cấp: </span>
                                <span>${providerBlock.provider.name}</span>
                            </div>
                            <div class="d-flex gap-2">
                                <span class="fw-medium">Xe: </span>
                                <span>${vehicle.name}</span>
                            </div>
                            <div class="d-flex gap-2">
                                <span class="fw-medium">Số chỗ: </span>
                                <span>${vehicle.seat_counting} chỗ</span>
                            </div>
                            <div class="d-flex gap-2">
                                <span class="fw-medium">Giá: </span>
                                <span>${Number(vehicle.price_per_day).toLocaleString('vi-VN')} VND</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="d-flex justify-content-center align-items-center h-100 w-100">
                            <a href="/booking/confirming?user=${userId}&provider=${providerBlock.provider.id}&vehicle=${vehicle.id}&route=${encodeURIComponent(route.route_name)}&from=${encodeURIComponent(route.from)}&to=${encodeURIComponent(route.to)}&km=${route.km}&price=${vehicle.price_per_day}"
                                    style="all: unset; display: inline-block; background-color: orange; color: white; 
                                        text-align: center; padding: 0.5rem 1rem; border-radius: 0.5rem; cursor: pointer;">
                                    Đặt xe ngay
                                </a>
                        </div>
                    </div>
                </div>
            </div>
            `;
                container.insertAdjacentHTML('beforeend', html);
            });
        });
    }


};

document.addEventListener('DOMContentLoaded', () => RouteSearch.init());
