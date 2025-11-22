const providersRelatedCostsHandler = {
    elements: {},
    init() {
        this.getElements();
        this.addEventElements();
    },
    getElements() {
        this.elements = {
            containerMessage: document.getElementById('messageContainer'),
            formLogin: document.getElementById('provider-related-costs'),
            loadingOverlay: document.getElementById('loadingOverlay'),
        };
    },

    addEventElements() {
        const form = this.elements.formLogin;
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(form);
            const data = Object.fromEntries(formData.entries());
            const { user_id } = data;

            console.log(data);

            // Hiển thị overlay loading
            this.showLoading();
            try {
                const res = await fetch(`/provider/${user_id}/extra-costs`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data)
                });

                const result = await res.json();

                this.hideLoading();

                if (result.status === 'success') {
                    this.showMessage(result.message, 'success');

                    // Chuyển trang sau 1 giây
                    setTimeout(() => {
                        window.location.href = result.redirect;
                    }, 1000);

                } else {
                    this.showMessage(result.message, 'error');
                }

            } catch (err) {
                this.hideLoading();
                this.showMessage('Có lỗi xảy ra, thử lại sau', 'error');
                console.error(err);
            }
        })
    },

    showMessage(message, type = 'success', duration = 3000) {
        // Tạo container nếu chưa có
        if (!this.elements.containerMessage) {
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
            this.elements.containerMessage = container;
        }

        const container = this.elements.containerMessage;

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

        container.appendChild(toast);

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

    showLoading() {
        this.elements.loadingOverlay.style.display = 'flex';
    },
    hideLoading() {
        this.elements.loadingOverlay.style.display = 'none';
    },

};

document.addEventListener('DOMContentLoaded', () => providersRelatedCostsHandler.init());
