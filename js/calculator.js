document.addEventListener("DOMContentLoaded", function () {
    const modelSelect = document.getElementById("model-select");
    const widthInput = document.getElementById("width");
    const heightInput = document.getElementById("height");
    const form = document.getElementById("calculator-contact-form");
    const formStatus = document.getElementById("form-status");
    const phoneInput = document.getElementById("phone");

    const results = {
        feetWidth: document.getElementById("feet-width"),
        feetHeight: document.getElementById("feet-height"),
        totalCabinets: document.getElementById("total-cabinets"),
        inchesWidth: document.getElementById("inches-width"),
        inchesHeight: document.getElementById("inches-height"),
        metersWidth: document.getElementById("meters-width"),
        metersHeight: document.getElementById("meters-height"),
        resWidth: document.getElementById("res-width"),
        resHeight: document.getElementById("res-height"),
        totalResolution: document.getElementById("total-resolution")
    };

    let currentConstants = {};

    // Phone number formatting
    phoneInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length > 0) {
            if (value.length <= 3) {
                value = `(${value}`;
            } else if (value.length <= 6) {
                value = `(${value.slice(0,3)}) ${value.slice(3)}`;
            } else {
                value = `(${value.slice(0,3)}) ${value.slice(3,6)}-${value.slice(6,10)}`;
            }
        }
        e.target.value = value;
    });

    function updateConstants() {
        const selectedOption = modelSelect.options[modelSelect.selectedIndex];
        
        if (selectedOption) {
            currentConstants = {
                F19: parseFloat(selectedOption.dataset.panelWidthInches) || 0,
                G19: parseFloat(selectedOption.dataset.panelHeightInches) || 0,
                F21: parseInt(selectedOption.dataset.resolutionWidth) || 0,
                G20: parseFloat(selectedOption.dataset.panelHeightMm) || 0,
                F20: parseFloat(selectedOption.dataset.panelWidthMm) || 0,
                G21: parseInt(selectedOption.dataset.resolutionHeight) || 0,
            };
        }
        
        calculate();
    }

    function calculate() {
        const D3 = parseFloat(widthInput.value) || 0;
        const E3 = parseFloat(heightInput.value) || 0;

        const D4 = D3 * currentConstants.F19 / 12;
        const E4 = E3 * currentConstants.G19 / 12;
        const G3 = D3 * E3;
        const D5 = D3 * currentConstants.F19;
        const E5 = E4 * 12;
        const D6 = D3 * currentConstants.F20 / 1000;
        const E6 = E3 * currentConstants.G20 / 1000;
        const D7 = D3 * currentConstants.F21;
        const E7 = E3 * currentConstants.G21;
        const G7 = D7 * E7;

        // Update results
        results.feetWidth.value = D4.toFixed(2);
        results.feetHeight.value = E4.toFixed(2);
        results.totalCabinets.value = G3;
        results.inchesWidth.value = D5.toFixed(2);
        results.inchesHeight.value = E5.toFixed(2);
        results.metersWidth.value = D6.toFixed(2);
        results.metersHeight.value = E6.toFixed(2);
        results.resWidth.value = D7;
        results.resHeight.value = E7;
        results.totalResolution.value = G7;
    }

    form.addEventListener('submit', function(e) {
        e.preventDefault();

        const calculatorData = {
            model: modelSelect.value,
            feet_width: results.feetWidth.value,
            feet_height: results.feetHeight.value,
            total_cabinets: results.totalCabinets.value,
            inches_width: results.inchesWidth.value,
            inches_height: results.inchesHeight.value,
            meters_width: results.metersWidth.value,
            meters_height: results.metersHeight.value,
            resolution_width: results.resWidth.value,
            resolution_height: results.resHeight.value,
            total_resolution: results.totalResolution.value
        };

        const formData = new FormData();
        formData.append('action', 'send_calculator_email');
        formData.append('nonce', calculatorAjax.nonce);
        formData.append('calculatorData', JSON.stringify(calculatorData)); // Stringify the object
        formData.append('firstName', form.querySelector('[name="firstName"]').value);
        formData.append('lastName', form.querySelector('[name="lastName"]').value);
        formData.append('email', form.querySelector('[name="email"]').value);
        formData.append('phone', form.querySelector('[name="phone"]').value);

        fetch(calculatorAjax.ajaxurl, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.data) {
                formStatus.className = 'form-status ' + (data.data.success ? 'success' : 'error');
                formStatus.textContent = data.data.message;
                
                if (data.data.success) {
                    form.reset();
                }
            }
        })
        .catch(error => {
            formStatus.className = 'form-status error';
            formStatus.textContent = 'An error occurred. Please try again.';
            console.error('Error:', error);
        });
    });

    modelSelect.addEventListener("change", updateConstants);
    widthInput.addEventListener("input", calculate);
    heightInput.addEventListener("input", calculate);

    // Initial setup
    updateConstants();
});