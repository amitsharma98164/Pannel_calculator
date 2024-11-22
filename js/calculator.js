document.addEventListener("DOMContentLoaded", function () {
    const widthInput = document.getElementById("width");
    const heightInput = document.getElementById("height");

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
        totalResolution: document.getElementById("total-resolution"),
        diagonal: document.getElementById("diagonal"),
        weight: document.getElementById("weight"),
        area: document.getElementById("area"),
        maxPower: document.getElementById("max-power"),
        powerCircuits: document.getElementById("power-circuits"),
    };

    const constants = {
        F19: 19.685, // Panel Size (WxHxD) inches Width
        G19: 39.37, // Panel Size (WxHxD) inches Height
        F21: 192, // Resolution Width
        G20: 1000, // Panel Size (WxHxD)mm Height
        F20: 500, // Panel Size (WxHxD)mm Width
        G21: 384, // Resolution Height
        F23: 15.2, // Weight LB
        F27: 150, // Max/AVG Power Consumption (watts)
    };

    function calculate() {
        const D3 = parseFloat(widthInput.value) || 0;
        const E3 = parseFloat(heightInput.value) || 0;

        const D4 = D3 * constants.F19 / 12;
        const E4 = E3 * constants.G19 / 12;
        const G3 = D3 * E3;
        const D5 = D3 * constants.F19;
        const E5 = E4 * 12;
        const D6 = D3 * constants.F20 / 1000;
        const E6 = E3 * constants.G20 / 1000;
        const D7 = D3 * constants.F21;
        const E7 = E3 * constants.G21;
        const G7 = D7 * E7;
        const G9 = D5 ** 2 + E5 ** 2;
        const G8 = Math.sqrt(G9);
        const G10 = G3 * constants.F23;
        const G11 = G3 * constants.F27;
        const G12 = Math.round(G11 / 120 / 18);

        // Update results
        results.feetWidth.textContent = D4.toFixed(2);
        results.feetHeight.textContent = E4.toFixed(2);
        results.totalCabinets.textContent = G3;
        results.inchesWidth.textContent = D5.toFixed(2);
        results.inchesHeight.textContent = E5.toFixed(2);
        results.metersWidth.textContent = D6.toFixed(2);
        results.metersHeight.textContent = E6.toFixed(2);
        results.resWidth.textContent = D7;
        results.resHeight.textContent = E7;
        results.totalResolution.textContent = G7;
        results.diagonal.textContent = G8.toFixed(2);
        results.area.textContent = G9.toFixed(2);
        results.weight.textContent = G10.toFixed(2);
        results.maxPower.textContent = G11.toFixed(2);
        results.powerCircuits.textContent = G12.toFixed(2);
    }

    widthInput.addEventListener("input", calculate);
    heightInput.addEventListener("input", calculate);

    calculate(); // Initial calculation
});