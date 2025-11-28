const TravelSpotHandler = {
    elements: {},
    init() {
        this.getElements();
        this.addEventElements();
    },
    getElements() {
        this.elements = {
            fromLocation: document.querySelector('#from_location'),
            toLocation: document.querySelector('#to_location'),
            btnConfirm: document.querySelector('#confirm_location')
        };
    },
    addEventElements() {
        const btnOpenPopUp = document.getElementById('booking_now');
        const btn = this.elements.btnConfirm;
        this.elements.fromLocation.addEventListener('change', () => {
            const from = this.elements.fromLocation.value;
            const to = this.elements.toLocation.value;
            btn.href = `/searching-routes?from=${from}&to=${to}`;
            if (from !== to && from !== "") {
                btn.style.pointerEvents = 'auto';
                btn.style.opacity = '1';
            } else {
                btn.style.pointerEvents = 'none';
                btn.style.opacity = '0.6';
            }
        });

        this.elements.btnConfirm.addEventListener('click', () => {
            const from = this.elements.fromLocation.value;
            const to = this.elements.toLocation.value;
            this.elements.btnConfirm.href = `/searching-routes?from=${from}&to=${to}`;
        });

        btnOpenPopUp.addEventListener('click', async () => {
            const destination = document.getElementById('destination');
            const inputDestination = document.getElementById('to_location');
            const path = window.location.pathname;
            const parts = path.split("/");
            const id = parts.pop();
            const from = this.elements.fromLocation.value;
            const btnConfirm = this.elements.btnConfirm;

            if (from === "") {
                btnConfirm.style.pointerEvents = 'none';
                btnConfirm.style.opacity = '0.6';
            };

            try {
                destination.innerHTML = `<span class="loading-spinner-travel-spot"></span`;
                const res = await fetch(`/travel-spot/${id}/province`);
                const { data } = await res.json();
                destination.innerHTML = `${data.travelSpot}, ${data.province.name}`;
                inputDestination.value = data.province.id;
            } catch (error) {
                destination.innerHTML = `<span style="color:red;">Lỗi tải dữ liệu</span>`;
            }
        })
    },


};

document.addEventListener('DOMContentLoaded', () => TravelSpotHandler.init());
