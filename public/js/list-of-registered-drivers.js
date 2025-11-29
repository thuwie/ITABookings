const ProviderManagement = {
    init() {
        this.addEventElements();
        this.getVehiclesAndRender();
        this.getUnverifiedDrivers();
    },

    addEventElements() {

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

    showLoading() {
        const loadingOverlay = document.getElementById('loadingOverlay');
        loadingOverlay.style.display = 'flex';
    },
    hideLoading() {
        const loadingOverlay = document.getElementById('loadingOverlay');
        loadingOverlay.style.display = 'none';
    },
    getVehiclesAndRender() {
        const tbody = document.getElementById('manageVehicleList');
        const providerId = document.getElementById('providerIdentity').value;
        try {
            const api = async () => {
                const res = await fetch(`/provider/${providerId}/vehicles`);
                const vehicles = await res.json();

                if (!Array.isArray(vehicles) || vehicles.length === 0) {
                    tbody.innerHTML = `
                    <tr>
                        <td colspan="6" style="text-align:center;">Không có xe nào</td>
                    </tr>`;
                    return;
                }

                // Render từng row
                let html = "";
                vehicles.forEach((v, idx) => {
                    html += `
                            <tr>
                                <td>${idx + 1}</td>
                                <td>${v.brand}</td>
                                <td>${v.model}</td>
                                <td>${v.license_plate}</td>
                                <td>${v.year_of_manufacture}</td>
                                <td>${v.seat_count}</td>
                                <td>${v.fuel_consumption}</td>
                                <td>${v.maintenance_per_km}</td>
                                <td>${v.vehicle_status === 'available'
                            ? '<span class="badge bg-success">Xe còn trống</span>'
                            : '<span class="badge bg-danger text-white">Xe đã được thuê</span>'}</td>
                                <td> <a href="${v.id}" class="btn btn-sm btn-primary">
                                        Xem chi tiết
                                </a></td>
                            </tr>`;
                });

                tbody.innerHTML = html;
            };

            api();

        } catch (error) {

        }
    },

    getUnverifiedDrivers() {
        const providerId = document.getElementById('provider_id').value;
        const tbody = document.getElementById('driverList');
        const api = async () => {
            const type = false;
            const res = await fetch(`/provider/${providerId}/${type}/drivers`);
            const drivers = await res.json();
            console.log(drivers);

            if (!Array.isArray(drivers) || drivers.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="6" style="text-align:center;">Không có tài xế nào</td>
                    </tr>`;
                return;
            }

            // Render từng row
            let html = "";
            drivers.forEach((v, idx) => {
                html += `
                            <tr>
                                <td>${idx + 1}</td>
                                <td>${v.first_name + v.last_name}</td>
                                <td>${(() => {
                        const birthDate = new Date(v.date_of_birth);
                        let age = new Date().getFullYear() - birthDate.getFullYear();
                        const m = new Date().getMonth() - birthDate.getMonth();
                        if (m < 0 || (m === 0 && new Date().getDate() < birthDate.getDate())) age--;
                        return age;
                    })()
                    }</td>
                                <td>${v.license_class}</td>
                                <td>${v.license_number}</td>
                                <td>${new Date(v.created_at).toLocaleDateString('vi-VN')}</td>
                                <td><span class="badge bg-warning">Chờ duyệt</span></td>
                                <td> <a href="${v.id}" class="btn btn-sm btn-primary">
                                        Xem chi tiết
                                </a></td>
                            </tr>`;
            });

            tbody.innerHTML = html;
        };
        api();
    },
    fetchData() {
        this.getVehiclesAndRender();
    }

};

document.addEventListener('DOMContentLoaded', () => ProviderManagement.init());
