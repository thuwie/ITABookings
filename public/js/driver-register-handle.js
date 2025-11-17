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
            businessSize: document.querySelector('#business-size')
        };
    },
    addEventElements() {
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
        )
    }


};

document.addEventListener('DOMContentLoaded', () => DriverRegisterHandler.init());
