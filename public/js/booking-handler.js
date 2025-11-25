const BookingHandler = {
    init() {
        this.addEventElements();
    },
    totalAmount: 0,
    totalDays: 0,
    addEventElements() {
        const startDay = document.getElementById('startDate');
        const endDay = document.getElementById('endDate');
        const form = document.getElementById('booking');
        const params = new URLSearchParams(window.location.search);
        const providerId = params.get('provider');
        const vehicleId = params.get('vehicle');

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(form);
            const dataForm = Object.fromEntries(formData.entries());
            const { user_id } = dataForm;
            const booking = {
                providerId,
                vehicleId,
                from: dataForm.pickup_point,
                to: dataForm.destination_point,
                distance: dataForm.total_km,
                fromDate: dataForm.start_date,
                toDate: dataForm.end_date,
                totalDays: BookingHandler.totalDays,
                totalAmount: BookingHandler.totalAmount,
                status: "pending"
            };

            BookingHandler.showLoading();

            try {
                const res = await fetch(`/booking/${user_id}`, {
                    method: 'POST',
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify(booking)
                });

                const result = await res.json();

                if (result.status === 'success') {
                    BookingHandler.showMessage(result.message, 'success');

                    // Chuyển trang sau 1 giây
                    setTimeout(() => {
                        window.location.href = result.redirect;
                        console.error(result.message);
                    }, 1000);

                } else {
                    window.location.href = result.redirect;
                }

                BookingHandler.hideLoading();
            } catch (err) {
                BookingHandler.hideLoading();
                console.error(err);
            };
        });

        startDay.addEventListener('change', BookingHandler.calculateTotal);
        endDay.addEventListener('change', BookingHandler.calculateTotal);

    },

    showMessage(message, type = 'success', duration = 3000) {
        const messageContainer = document.getElementById('messageContainer');
        if (!messageContainer) {
            const container = document.createElement('div');
            container.id = 'toastContainer';
            container.style.position = 'fixed';
            container.style.top = '1rem'; // cao hơn để không che form
            container.style.right = '1rem';
            container.style.zIndex = '1050'; // cao hơn hầu hết element
            container.style.display = 'flex';
            container.style.flexDirection = 'column';
            container.style.gap = '0.5rem';
            document.body.appendChild(container);
            messageContainer = container;
        }

        // Tạo toast
        const toast = document.createElement('div');
        toast.className = `toast d-flex align-items-center text-white border-0`;
        toast.style.backgroundColor = type === 'success' ? '#28a745' : '#dc3545';
        toast.style.padding = '0.75rem 1rem';
        toast.style.borderRadius = '0.25rem';
        toast.style.minWidth = '250px';
        toast.style.boxShadow = '0 0.25rem 0.75rem rgba(0,0,0,0.1)';
        toast.style.opacity = 0;
        toast.style.transform = 'translateX(100%)';
        toast.style.transition = 'transform 0.4s ease, opacity 0.4s ease';
        toast.style.display = 'flex';
        toast.style.alignItems = 'center';
        toast.style.justifyContent = 'space-between';

        const icon = type === 'success'
            ? '<i class="bi bi-check-circle-fill me-2"></i>'
            : '<i class="bi bi-exclamation-triangle-fill me-2"></i>';

        toast.innerHTML = `
        <div class="d-flex align-items-center">
            ${icon}
            <span>${message}</span>
        </div>
       <span class="btn-close btn-close-white" style="cursor:pointer; "></span>
    `;

        messageContainer.appendChild(toast);

        // Trượt vào
        requestAnimationFrame(() => {
            toast.style.transform = 'translateX(0)';
            toast.style.opacity = 1;
        });

        // Click nút đóng
        toast.querySelector('.btn-close').addEventListener('click', () => {
            toast.style.transform = 'translateX(100%)';
            toast.style.opacity = 0;
            setTimeout(() => toast.remove(), 400);
        });

        // // Tự biến mất sau duration
        setTimeout(() => {
            toast.style.transform = 'translateX(100%)';
            toast.style.opacity = 0;
            setTimeout(() => toast.remove(), 400);
        }, duration);
    },

    calculateTotal() {
        const content = document.getElementById('total_price');
        const startDay = document.getElementById('startDate');
        const endDay = document.getElementById('endDate');
        const btnSubmit = document.getElementById('btn-submit');
        let startDate = BookingHandler.parseDate(startDay.value);
        let endDate = BookingHandler.parseDate(endDay.value);
        const priceOneDay = Number(document.getElementById('price_one_day').value || 0);

        if (!startDate || !endDate) {
            content.innerHTML = `0 đ`;
            return;
        }

        if (startDate < endDate) {
            btnSubmit.style.opacity = 1;
            btnSubmit.style.pointerEvents = 'auto';
            const diffTime = endDate - startDate; // milliseconds
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)); // +1 nếu tính cả ngày đầu và cuối
            let result = diffDays * priceOneDay;
            result = Math.round(result);

            BookingHandler.totalAmount = result;
            BookingHandler.totalDays = diffDays;
            content.innerHTML = `${result.toLocaleString()} đ`;
        };


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
