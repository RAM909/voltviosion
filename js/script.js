// Solar Energy Production Calculator
document.getElementById("calculateEnergy").addEventListener("click", function () {
    const panelCapacity = parseFloat(document.getElementById("panelCapacity").value);
    const sunlightHours = parseFloat(document.getElementById("sunlightHours").value);

    if (isNaN(panelCapacity) || isNaN(sunlightHours) || panelCapacity <= 0 || sunlightHours <= 0) {
        alert("Please enter valid positive numbers for Solar Panel Capacity and Sunlight Hours.");
        return;
    }

    const dailyOutput = panelCapacity * sunlightHours; // kWh/day
    const monthlyProduction = dailyOutput * 30; // kWh/month

    document.getElementById("dailyOutput").innerText = dailyOutput.toFixed(2);
    document.getElementById("monthlyProduction").innerText = monthlyProduction.toFixed(2);
});

// Savings from Solar Calculator
document.getElementById("calculateSavings").addEventListener("click", function () {
    const panelCapacity = parseFloat(document.getElementById("savingsPanelCapacity").value);
    const sunlightHours = parseFloat(document.getElementById("sunlightHoursSavings").value);
    const electricityTariff = parseFloat(document.getElementById("electricityTariff").value);

    if (isNaN(panelCapacity) || isNaN(sunlightHours) || isNaN(electricityTariff) || panelCapacity <= 0 || sunlightHours <= 0 || electricityTariff <= 0) {
        alert("Please enter valid positive numbers for all fields.");
        return;
    }

    const dailyProduction = panelCapacity * sunlightHours; // kWh/day
    const dailySavings = dailyProduction * electricityTariff; // ₹/day
    const monthlySavings = dailySavings * 30; // ₹/month

    document.getElementById("dailySavings").innerText = dailySavings.toFixed(2);
    document.getElementById("monthlySavings").innerText = monthlySavings.toFixed(2);
});

// ROI Calculator
document.getElementById("calculateROI").addEventListener("click", function () {
    const systemCost = parseFloat(document.getElementById("systemCost").value);
    const monthlySavings = parseFloat(document.getElementById("monthlySavingsROI").value);

    if (isNaN(systemCost) || isNaN(monthlySavings) || systemCost <= 0 || monthlySavings <= 0) {
        alert("Please enter valid positive numbers for Cost of Solar System and Monthly Savings.");
        return;
    }

    const payoffTime = systemCost / monthlySavings; // months
    const annualSavings = monthlySavings * 12; // ₹/year

    document.getElementById("payoffTime").innerText = payoffTime.toFixed(2);
    document.getElementById("annualSavings").innerText = annualSavings.toFixed(2);
});
