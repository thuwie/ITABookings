document.addEventListener('DOMContentLoaded', () => {
    // MOCK DATA
    const drivers = [
        {
            id: 1,
            name: 'Nguyen Van B',
            age: 28,
            licenseType: 'B2',
            experience: '3 năm lái xe tải',
            submittedDate: '2025-11-20',
            status: 'pending'
        },
        {
            id: 2,
            name: 'Tran Thi C',
            age: 32,
            licenseType: 'C',
            experience: '5 năm lái xe khách',
            submittedDate: '2025-11-21',
            status: 'pending'
        },
        {
            id: 3,
            name: 'Le Van D',
            age: 24,
            licenseType: 'B1',
            experience: '2 năm lái xe taxi',
            submittedDate: '2025-11-21',
            status: 'pending'
        }
    ];

    const driverListEl = document.getElementById('driverList');

    function showMessage(message, type='success', duration=3000) {
        let container = document.getElementById('toastContainer');
        if(!container) {
            container = document.createElement('div');
            container.id = 'toastContainer';
            container.style.position = 'fixed';
            container.style.top = '1rem';
            container.style.right = '1rem';
            container.style.zIndex = '1050';
            container.style.display = 'flex';
            container.style.flexDirection = 'column';
            container.style.gap = '0.5rem';
            document.body.appendChild(container);
        }

        const toast = document.createElement('div');
        toast.style.backgroundColor = type==='success' ? '#28a745' : '#dc3545';
        toast.style.color = '#fff';
        toast.style.padding = '0.75rem 1rem';
        toast.style.borderRadius = '0.25rem';
        toast.style.boxShadow = '0 0.25rem 0.75rem rgba(0,0,0,0.1)';
        toast.style.display = 'flex';
        toast.style.justifyContent = 'space-between';
        toast.style.alignItems = 'center';
        toast.style.opacity = 0;
        toast.style.transition = 'all 0.4s ease';

        toast.innerHTML = `<span>${message}</span><span style="cursor:pointer;">&times;</span>`;
        container.appendChild(toast);

        setTimeout(()=>{ toast.style.opacity=1; }, 50);
        toast.querySelector('span:last-child').addEventListener('click', ()=>{ toast.remove(); });
        setTimeout(()=>{ toast.remove(); }, duration);
    }

    function renderDrivers() {
        driverListEl.innerHTML = '';
        drivers.forEach(driver => {
            const tr = document.createElement('tr');

            tr.innerHTML = `
                <td>${driver.name}</td>
                <td>${driver.age}</td>
                <td>${driver.licenseType}</td>
                <td>${driver.experience}</td>
                <td>${driver.submittedDate}</td>
                <td>
                    <button class="view-btn" data-id="${driver.id}">Xem chi tiết</button>
                    <button class="btn-accept" data-id="${driver.id}">Xác nhận</button>
                    <button class="btn-reject" data-id="${driver.id}">Từ chối</button>
                </td>
            `;

            driverListEl.appendChild(tr);
        });

        // Action listeners
        document.querySelectorAll('.view-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const id = parseInt(btn.dataset.id);
                const driver = drivers.find(d => d.id === id);
                alert(`Chi tiết tài xế:\nTên: ${driver.name}\nTuổi: ${driver.age}\nGPLX: ${driver.licenseType}\nKinh nghiệm: ${driver.experience}\nNgày nộp: ${driver.submittedDate}`);
            });
        });

        document.querySelectorAll('.btn-accept').forEach(btn => {
            btn.addEventListener('click', () => {
                const id = parseInt(btn.dataset.id);
                const driver = drivers.find(d => d.id === id);
                driver.status = 'accepted';
                showMessage(`Tài xế ${driver.name} đã được xác nhận`, 'success');
                renderDrivers();
            });
        });

        document.querySelectorAll('.btn-reject').forEach(btn => {
            btn.addEventListener('click', () => {
                const id = parseInt(btn.dataset.id);
                const driver = drivers.find(d => d.id === id);
                const reason = prompt(`Nhập lý do từ chối tài xế ${driver.name}:`);
                if(reason) {
                    driver.status = 'rejected';
                    driver.rejectReason = reason;
                    showMessage(`Tài xế ${driver.name} bị từ chối. Lý do: ${reason}`, 'error');
                    renderDrivers();
                }
            });
        });
    }

    renderDrivers();
});
