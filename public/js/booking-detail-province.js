const BookingProvinceDetail = {
    elements: {},
    init() {
        this.getElements();
        this.disableConfirmAsOpenModel();
        this.addEventElements();
    },
    disableConfirmAsOpenModel() {
        const from = this.elements.fromLocation.value;
        const btnConfirm = this.elements.btnConfirm;
        if (from === "") {
            btnConfirm.disabled = true;
        };
    },
    getElements() {
        this.elements = {
            fromLocation: document.querySelector('#from_location'),
            toLocation: document.querySelector('#to_location'),
            btnConfirm: document.querySelector('#confirm_location')
        };
    },
    addEventElements() {
        const btn = this.elements.btnConfirm;
        this.elements.fromLocation.addEventListener('change', () => {
            const from = this.elements.fromLocation.value;
            const to = this.elements.toLocation.value;
            btn.href = `/searching-routes?from=${from}&to=${to}`;
            const btnConfirm = this.elements.btnConfirm;
            if (from !== to && from !== "") {
                btnConfirm.disabled = false;
            } else {
                btnConfirm.disabled = true;
            }
        });

        this.elements.btnConfirm.addEventListener('click', () => {
            const from = this.elements.fromLocation.value;
            const to = this.elements.toLocation.value;
            this.elements.btnConfirm.href = `/searching-routes?from=${from}&to=${to}`;
        });
    },


};

document.addEventListener('DOMContentLoaded', () => BookingProvinceDetail.init());
