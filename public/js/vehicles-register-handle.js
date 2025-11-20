const VehicleRegisterHandler = {
    elements: {},
    init() {
        this.getElements();
        this.fetch();
        this.addEventElements();
    },
    getElements() {
        this.elements = {
            containerMessage: document.getElementById('messageContainer'),
            form: document.getElementById('vehicle-form'),
            loadingOverlay: document.getElementById('loadingOverlay'),
            files: document.getElementById('files'),
            imgsPreview: document.getElementById('imagesPreview'),
            allUtilityRadio: document.querySelectorAll('input[name="utility"]'),
            selectAvailableUtilitiesElement: document.getElementById('utilities-radio'),
            containerAddNewUtilities: document.getElementById('multiple_utilities'),
        };
    },
    addEventElements() {
        const form = this.elements.form;
        const inputImages = this.elements.files;
        const previewContainer = this.elements.imgsPreview;
        const allUtilityRadio = this.elements.allUtilityRadio;
        const selectAvailableUtilitiesElement = this.elements.selectAvailableUtilitiesElement;
        const containerAddNewUtilities = this.elements.containerAddNewUtilities;


        form.addEventListener('submit', async (e) => {

            e.preventDefault();
            const formData = new FormData(e.target);
            const containerAddNewUtilities = this.elements.containerAddNewUtilities;
            const selectAvailableUtilitiesElement = this.elements.selectAvailableUtilitiesElement;

            const allData = Object.fromEntries(
                [...formData.entries()].filter(([key, value]) => !(value instanceof File))
            );

            const user_id = allData.user_id;

            const files = inputImages.files;
            // Tách object con
            let vehicleInfo = {
                brand: allData.brand,
                model: allData.model,
                year_of_manufacture: allData.year_of_manufacture,
                seat_counting: allData.seat_counting,
                fuel_consumption: allData.fuel_consumption,
                maintenance_per_km: allData.maintenance_per_km,
                description: allData.description,
                license_plate: allData.license_plate
            };

            if (allData.utility === "0") {
                const inputs = containerAddNewUtilities.querySelectorAll('input');

                // Extract their values into an array
                const values = Array.from(inputs).map(input => input.value);
                vehicleInfo = { ...vehicleInfo, utilities: values, newUtility: true };
            } else {
                // Select only checked checkboxes
                const checkedBoxes = selectAvailableUtilitiesElement.querySelectorAll('input[type="checkbox"]:checked');

                // Extract their values
                const checkedValues = Array.from(checkedBoxes).map(cb => cb.value);
                vehicleInfo = { ...vehicleInfo, utilities: checkedValues, newUtility: false };
            }

            formData.set("vehicle-information", JSON.stringify(vehicleInfo));
            for (let i = 0; i < files.length; i++) {
                formData.append("files[]", files[i]);
            }

            // // Hiển thị overlay loading
            this.showLoading();

            try {
                const res = await fetch(`/provider/${user_id}/vehicles`, {
                    method: 'POST',
                    body: formData
                });

                const result = await res.json();

                this.hideLoading();

                if (result.status === 'success') {
                    VehicleRegisterHandler.fetch();
                    form.reset();
                    this.showMessage(result.message, 'success');

                    // // Chuyển trang sau 1 giây
                    // setTimeout(() => {
                    //     window.location.href = result.redirect;
                    // }, 1000);

                } else {
                    this.showMessage(result.message, 'error');
                }

            } catch (err) {
                this.hideLoading();
                this.showMessage('Có lỗi xảy ra, thử lại sau', 'error');
                console.error(err);
            }
        });

        files.addEventListener('change', function (event) {

            previewContainer.innerHTML = "";

            const files = event.target.files;

            if (files.length > 0) {
                previewContainer.style.display = "block";
            } else {
                previewContainer.style.display = "none";
                return;
            }

            Array.from(files).forEach(file => {
                if (!file.type.startsWith('image/')) return;

                const img = document.createElement('img');
                img.src = URL.createObjectURL(file);
                img.style.maxWidth = "150px";
                img.style.marginRight = "10px";
                img.style.marginBottom = "10px";
                img.style.border = "1px solid #ccc";
                img.style.padding = "5px";
                img.style.borderRadius = "6px";

                previewContainer.appendChild(img);
            });
        });

        allUtilityRadio.forEach(radio => {
            radio.addEventListener('change', (e) => {
                const target = e.target.value;

                if (target === "0") {
                    this.enableUtilities(containerAddNewUtilities);
                    this.disableUtilities(selectAvailableUtilitiesElement);
                } else {
                    this.enableUtilities(selectAvailableUtilitiesElement);
                    this.disableUtilities(containerAddNewUtilities);
                }
            })
        })

        containerAddNewUtilities.addEventListener('click', function (e) {
            if (e.target.classList.contains("plus-icon")) {
                VehicleRegisterHandler.addUtilityRow();
            }
        })

    },

    addUtilityRow() {
        const containerAddNewUtilities = this.elements.containerAddNewUtilities;
        const childCounting = containerAddNewUtilities.children.length;
        if (childCounting <= 4) {
            const newDiv = document.createElement('div');
            const iTag = document.createElement('i');
            const newInput = document.createElement('input');

            newDiv.classList.add('d-flex', 'gap-2', 'align-items-center');
            newInput.setAttribute("type", "text");
            newInput.setAttribute('required', true);
            newInput.id = `utility-${childCounting + 1}`;
            newInput.name = `utility-${childCounting + 1}`;

            iTag.className = "bi bi-plus fw-bold text-success fs-5 plus-icon";

            newDiv.appendChild(newInput);
            newDiv.appendChild(iTag);

            containerAddNewUtilities.appendChild(newDiv);
        }
    },

    disableUtilities(container) {
        // Disable all form fields inside
        container.querySelectorAll('input, select, textarea, button').forEach(el => {
            el.disabled = true;
        });

        // Disable clicking the plus icon
        container.style.pointerEvents = "none";
        container.style.opacity = "0.6";
    },

    enableUtilities(container) {
        container.querySelectorAll('input, select, textarea, button').forEach(el => {
            el.disabled = false;
        });

        container.style.pointerEvents = "auto";
        container.style.opacity = "1";
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

    fetch() {
        const selectAvailableUtilitiesElement = this.elements.selectAvailableUtilitiesElement;

        const utilities = async () => {
            try {
                const res = await fetch(`/provider/utilities`, {
                    method: 'GET',
                });

                const result = await res.json();

                if (result.status === 'success') {
                    const { data } = result;
                    const children = data.map((util) => {
                        const newLabel = document.createElement('label');
                        const newInput = document.createElement('input');
                        const newSpan = document.createElement('span');
                        newLabel.className = "d-flex gap-2 align-items-center";
                        newInput.type = "checkbox";
                        newInput.name = "utilities_select";
                        newInput.value = util.id;
                        newInput.style = "width: 12px; height: 12px;";
                        newSpan.className = "fw-lighter";
                        newSpan.innerHTML = util.name.charAt(0).toUpperCase() + util.name.slice(1);
                        newLabel.appendChild(newInput);
                        newLabel.appendChild(newSpan);

                        return newLabel;
                    }
                    )

                    VehicleRegisterHandler.fetchElement();

                    children.forEach(child => selectAvailableUtilitiesElement.appendChild(child));


                } else {
                    console.log(result.status);
                }

            } catch (err) {
                console.error(err);
            }
        }

        utilities();
    },

    fetchElement() {
        const selectAvailableUtilitiesElement = this.elements.selectAvailableUtilitiesElement;
        const containerAddNewUtilities = this.elements.containerAddNewUtilities;
        const previewContainer = this.elements.imgsPreview;

        previewContainer.style = "display: none";
        VehicleRegisterHandler.enableUtilities(selectAvailableUtilitiesElement);
        VehicleRegisterHandler.disableUtilities(containerAddNewUtilities);
        containerAddNewUtilities.style = "pointer-events: none; opacity: 0.6;";

        while (containerAddNewUtilities.children.length > 1) {
            containerAddNewUtilities.removeChild(containerAddNewUtilities.lastElementChild);
        }

        selectAvailableUtilitiesElement.innerHTML = "";
    }

}

document.addEventListener('DOMContentLoaded', () => VehicleRegisterHandler.init());

