
// Lấy data từ form được truyền từ server
const travelSpots = window.travelSpots;


// Lấy select
const provinceSelect = document.getElementById('province_id');
const travelSpotSelect = document.getElementById('travel_spot_id');

// Lắng nghe khi chọn province
provinceSelect.addEventListener('change', function () {
    const provinceId = this.value;
    travelSpotSelect.innerHTML = '';
    const filterTravelSpots = travelSpots.filter((spot) => spot.province_id === Number(provinceId));
    filterTravelSpots.forEach((opt) => {
        const option = document.createElement('option');
        option.value = opt.id;
        option.textContent = opt.name;
        travelSpotSelect.appendChild(option);
    });
    const noneOption = document.createElement('option');
    noneOption.value = 0;
    noneOption.textContent = "Không thuộc địa điểm du lịch nào";
    travelSpotSelect.appendChild(noneOption);
});
