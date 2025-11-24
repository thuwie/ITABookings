const ProviderRegisterHandler = {
    elements: {},
    init() {
        this.getElements();
        this.addEventElements();
    },
    getElements() {
        this.elements = {
            containerMessage: document.getElementById('messageContainer'),
            form: document.getElementById('businessForm'),
            loadingOverlay: document.getElementById('loadingOverlay'),
            logoInput: document.getElementById('logo'),
            qrInput: document.getElementById('qr_image')
        };
    },

    addEventElements() {
        const form = this.elements.form;
        const logoInput = this.elements.logoInput;
        const qrInput = this.elements.qrInput;

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(e.target);

            // Tách toàn bộ data text
            const allData = Object.fromEntries(
                [...formData.entries()].filter(([key, value]) => !(value instanceof File))
            );

            // Tách file
            const files = { logo: null, qr: null };
            for (let [key, value] of formData.entries()) {
                if (value instanceof File && value.name !== "") {
                    if (key === "logo") files.logo = value;
                    if (key === "qr_image") files.qr = value;
                }
            }


            // Tách object con
            const providerInfo = {
                name: allData.name,
                email: allData.email,
                phone_number: allData.phone_number,
                address: allData.address,
                province_id: allData.province_id,
                description: allData.description
            };

            const paymentInfo = {
                bank_name: allData.bank_name,
                account_number: allData.account_number,
                full_name_account: allData.full_name_account.toUpperCase(),
            };

            formData.set("provider-information", JSON.stringify(providerInfo));
            formData.set("logo", files.logo);
            formData.set("payment-information", JSON.stringify(paymentInfo));
            formData.set("qr", files.qr);

            // Hiển thị overlay loading
            this.showLoading();

            try {
                const res = await fetch('/provider/register', {
                    method: 'POST',
                    body: formData
                });

                const result = await res.json();

                this.hideLoading();

                if (result.status === 'success') {
                    this.showMessage(result.message, 'success');

                    console.log(result.redirect);

                    // // Chuyển trang sau 1 giây
                    setTimeout(() => {
                        window.location.href = result.redirect;
                    }, 1000);

                } else {
                    console.log(result.redirect);

                    window.location.href = result.redirect;
                }

            } catch (err) {
                this.hideLoading();
                this.showMessage('Có lỗi xảy ra, thử lại sau', 'error');
                console.error(err);
            }
        });

        logoInput.addEventListener('change', () => {
            this.previewImage(
                logoInput,
                document.getElementById('logoPreviewImg'),
                document.getElementById('logoPreview')
            );
        });

        // Gọi hàm preview cho QR
        qrInput.addEventListener('change', () => {
            this.previewImage(
                qrInput,
                document.getElementById('qrImagePreview'),
                document.getElementById('qrPreview')
            );
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

    previewImage(inputElement, previewImgElement, previewContainer) {
        const file = inputElement.files[0];
        if (!file) {
            previewContainer.style.display = "none";
            previewImgElement.src = "";
            return;
        }

        const reader = new FileReader();
        reader.onload = function (e) {
            previewImgElement.src = e.target.result;
            previewContainer.style.display = "block";
        };
        reader.readAsDataURL(file);
    }
};

document.addEventListener('DOMContentLoaded', () => ProviderRegisterHandler.init());


