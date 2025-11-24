const DriverRegisterHandler = {
    elements: {},
    init() {
        this.getElements();
        this.addEventElements();
    },
    getElements() {
        this.elements = {
            selectedProvider: document.querySelector('#providers'),
            nameBusiness: document.querySelector('#name-business'),
            descriptionBusiness: document.querySelector('#description-business'),
            logoBusiness: document.querySelector('#logoPreviewImg'),
            emailBusiness: document.querySelector('#email-business'),
            phoneBusiness: document.querySelector("#phone_number_business"),
            provinceBusiness: document.querySelector('#province_business'),
            addressBusiness: document.querySelector('#address-business'),
            businessSize: document.querySelector('#business-size'),
            containerMessage: document.getElementById('messageContainer'),
            form: document.getElementById('driverForm'),
            loadingOverlay: document.getElementById('loadingOverlay'),
            file: document.getElementById('qr_image'),
            qrInput: document.getElementById('qrImagePreview'),
            previewContainer: document.getElementById('qrPreview')
        };
    },
    addEventElements() {
        const form = this.elements.form;
        const file = this.elements.file;
        const uploadedFile = file.files[0];
        const previewContainer = this.elements.previewContainer;
        const imgPreview = this.elements.qrInput;
        this.elements.selectedProvider.addEventListener('change', async (e) => {
            const id = e.target.value;

            // DOM
            const loader = document.getElementById("provider-loader");
            const details = document.getElementById("provider-details");

            // Step 1: hide details, show loader
            details.style.display = "none";
            loader.style.display = "block";

            try {
                const res = await fetch(`/provider/${id}`, {
                    method: "GET",
                    headers: {
                        "Accept": "application/json"
                    }
                });

                const data = await res.json();

                if (!data.success) {
                    console.error("Failed to load provider");
                    return;
                }

                const { provider } = data;
                console.log(provider);

                this.elements.nameBusiness.innerHTML = provider.name;
                this.elements.descriptionBusiness.innerHTML = provider.description;
                this.elements.logoBusiness.src = provider.logo_url;
                this.elements.emailBusiness.innerHTML = provider.email;
                this.elements.phoneBusiness.innerHTML = provider.phone_number;
                this.elements.addressBusiness.value = provider.address;
                this.elements.provinceBusiness.value = provider.province_id;

                loader.style.display = "none";
                details.style.display = "block";

            } catch (error) {
                console.error("Error loading provider:", error);
                loader.style.display = "none";
            }
        }
        );

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(e.target);

            const allData = Object.fromEntries(
                [...formData.entries()].filter(([key, value]) => !(value instanceof File))
            );

            // Tách object con
            const driverInfo = {
                provider_id: allData.providers,
                license_number: allData.license_number,
                license_class: allData.license_class,
                license_issue_date: allData.license_issue_date,
                license_expiry_date: allData.license_expiry_date,
                status: allData.status
            };

            const paymentInfo = {
                bank_name: allData.bank_name,
                account_number: allData.account_number,
                full_name_account: allData.full_name_account.toUpperCase(),
            };

            formData.set("driver-information", JSON.stringify(driverInfo));
            formData.set("payment-information", JSON.stringify(paymentInfo));
            formData.set("qr", uploadedFile);

            // Hiển thị overlay loading
            this.showLoading();

            try {
                const res = await fetch('/driver/register', {
                    method: 'POST',
                    body: formData
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
                    window.location.href = result.redirect;
                }

            } catch (err) {
                this.hideLoading();
                this.showMessage('Có lỗi xảy ra, thử lại sau', 'error');
                console.error(err);
            }
        });

        file.addEventListener('change', (e) => {
            const inputElement = e.target;
            this.previewImage(inputElement, imgPreview, previewContainer);
        });

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
    },

    showLoading() {
        this.elements.loadingOverlay.style.display = 'flex';
    },

    hideLoading() {
        this.elements.loadingOverlay.style.display = 'none';
    },

};

document.addEventListener('DOMContentLoaded', () => DriverRegisterHandler.init());
