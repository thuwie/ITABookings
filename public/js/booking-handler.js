const BookingHandler = {
    init() {
        this.addEventElements();
    },


    addEventElements() {
        const startDay = document.getElementById('startDate');
        const endDay = document.getElementById('endDate');
        // form.addEventListener('submit', async (e) => {
        //     e.preventDefault();
        //     BookingHandler.showLoading();
        //     const res = await fetch(``);
        //     const routes = await res.json();
        //     const { data } = routes;

        //     BookingHandler.hideLoading();
        // })

        startDay.addEventListener('change', BookingHandler.calculateTotal);
        endDay.addEventListener('change', BookingHandler.calculateTotal);

    },
    calculateTotal() {
        const content = document.getElementById('total_price');
        const startDay = document.getElementById('startDate');
        const endDay = document.getElementById('endDate');
        let startDate = BookingHandler.parseDate(startDay.value);
        let endDate = BookingHandler.parseDate(endDay.value);
        const priceOneDay = Number(document.getElementById('price_one_day').value || 0);

        if (!startDate || !endDate) {
            content.innerHTML = `0 đ`;
            return;
        }

        if (startDate > endDate) {
            // swap nếu startDate lớn hơn endDate
            const temp = startDate;
            startDate = endDate;
            endDate = temp;
        }

        const diffTime = endDate - startDate; // milliseconds
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)); // +1 nếu tính cả ngày đầu và cuối
        let result = diffDays * priceOneDay;
        console.log(result);
        console.log(diffDays);



        result = Math.round(result);

        content.innerHTML = `${result.toLocaleString()} đ`;
    },

    parseDate(input) {
        // input dạng "YYYY-MM-DD"
        const parts = input.split('-');
        if (parts.length !== 3) return null;
        return new Date(parts[0], parts[1] - 1, parts[2]); // tháng JS từ 0-11
    },

    showLoading() {
        const loadingOverlay = document.getElementById('loadingOverlay');
        loadingOverlay.style.display = 'flex';
    },
    hideLoading() {
        const loadingOverlay = document.getElementById('loadingOverlay');
        loadingOverlay.style.display = 'none';
    },

};

document.addEventListener('DOMContentLoaded', () => BookingHandler.init());
