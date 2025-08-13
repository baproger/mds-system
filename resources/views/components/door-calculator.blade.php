<div class="calculator-container">
    <div class="calculator-header">
        <h3 class="calculator-title">
            <i class="fas fa-calculator"></i>
            Калькулятор стоимости двери
        </h3>
        <p class="calculator-subtitle">Рассчитайте стоимость двери с учетом всех параметров</p>
    </div>
    
    <div class="calculator-form">
        <div class="form-group">
            <label for="category" class="form-label">
                <i class="fas fa-tag"></i>
                Категория двери
            </label>
            <select id="category" onchange="loadModels()" class="form-select">
                <option value="">Выберите категорию</option>
                <option value="Lux">Lux</option>
                <option value="Premium">Premium</option>
                <option value="Comfort">Comfort</option>
            </select>
        </div>

        <div class="form-group">
            <label for="model" class="form-label">
                <i class="fas fa-cube"></i>
                Модель
            </label>
            <select id="model" disabled class="form-select"></select>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="height" class="form-label">
                    <i class="fas fa-arrows-alt-v"></i>
                    Высота двери (м)
                </label>
                <input type="number" id="height" step="0.01" placeholder="2.40" value="2.40" class="form-input">
            </div>
            <div class="form-group">
                <label for="width" class="form-label">
                    <i class="fas fa-arrows-alt-h"></i>
                    Ширина двери (м)
                </label>
                <input type="number" id="width" step="0.01" placeholder="1.80" value="1.80" class="form-input">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="discount" class="form-label">
                    <i class="fas fa-percentage"></i>
                    Скидка (%)
                </label>
                <input type="number" id="discount" step="1" value="0" min="0" max="100" oninput="calculate()" class="form-input">
            </div>
            <div class="form-group">
                <label for="installation" class="form-label">
                    <i class="fas fa-tools"></i>
                    Установка (₸)
                </label>
                <input type="number" id="installation" step="100" placeholder="10000" class="form-input">
            </div>
        </div>

        <div class="form-group">
            <label class="checkbox-label">
                <input type="checkbox" id="thermalBreak" onchange="calculate()" class="form-checkbox">
                <span class="checkmark"></span>
                <i class="fas fa-thermometer-half"></i>
                Терморазрыв (70,000 ₸/м²)
            </label>
        </div>

        <button onclick="calculate()" class="calculate-btn">
            <i class="fas fa-calculator"></i>
            Рассчитать стоимость
        </button>
    </div>

    <div class="calculator-result" id="output" style="display:none;"></div>
</div>

<style>
.calculator-container {
    background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
    border: 1px solid #e2e8f0;
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    max-width: 600px;
    margin: 0 auto;
}

.calculator-header {
    text-align: center;
    margin-bottom: 2rem;
    padding-bottom: 1.5rem;
    border-bottom: 2px solid #f1f5f9;
}

.calculator-title {
    font-size: 1.5rem;
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.75rem;
}

.calculator-subtitle {
    color: #64748b;
    font-size: 0.875rem;
    margin: 0;
}

.calculator-form {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.form-label {
    font-size: 0.875rem;
    font-weight: 500;
    color: #374151;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.form-select,
.form-input {
    padding: 0.75rem 1rem;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    font-size: 0.875rem;
    transition: all 0.3s ease;
    background: #ffffff;
    color: #1f2937;
    font-family: inherit;
}

.form-select:focus,
.form-input:focus {
    outline: none;
    border-color: #1ba4e9;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.form-select:disabled {
    background: #f1f5f9;
    color: #9ca3af;
    cursor: not-allowed;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}

.checkbox-label {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    cursor: pointer;
    padding: 0.75rem;
    border-radius: 12px;
    background: #f8fafc;
    border: 2px solid #e2e8f0;
    transition: all 0.3s ease;
}

.checkbox-label:hover {
    background: #f1f5f9;
    border-color: #cbd5e0;
}

.form-checkbox {
    width: 1.25rem;
    height: 1.25rem;
    accent-color: #1ba4e9;
}

.calculate-btn {
    background: linear-gradient(135deg, #1ba4e9 0%, #ac76e3 100%);
    color: white;
    border: none;
    padding: 1rem 2rem;
    border-radius: 12px;
    font-weight: 600;
    font-size: 0.875rem;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.75rem;
    margin-top: 1rem;
}

.calculate-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
}

.calculator-result {
    margin-top: 2rem;
    padding: 1.5rem;
    background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
    border-radius: 15px;
    border: 2px solid #0ea5e9;
}

@media (max-width: 768px) {
    .calculator-container {
        padding: 1.5rem;
        margin: 1rem;
    }
    
    .form-row {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
const data = {
  "Lux": {
    "Англия": 458553,
    "Империал": 476190,
    "Бастион / Хайтек": 493827,
    "Фьюжн / Хайтек": 432099,
    "Фьюжн сложный": 529100,
    "Лион": 405643,
    "Агора": 335097,
    "Ажур": 343916,
    "Горизонт": 335097,
    "Остиум (Армада люкс)": 405643,
    "Армада": 388007,
    "Акрополь": 291006,
    "Эксклюзив": 670194
  },
  "Premium": {
    "Англия": 405643,
    "Империал": 423280,
    "Бастион / Хайтек": 440917,
    "Фьюжн / Хайтек": 379189,
    "Фьюжн сложный": 476190,
    "Лион": 352733,
    "Агора": 282187,
    "Ажур": 291006,
    "Горизонт": 282187,
    "Остиум (Армада люкс)": 352733,
    "Армада": 335097,
    "Акрополь": 238096
  },
  "Comfort": {
    "Англия": 335097,
    "Империал": 352733,
    "Бастион / Хайтек": 370370,
    "Фьюжн / Хайтек": 335097,
    "Лион": 282187,
    "Агора": 211640,
    "Ажур": 220459,
    "Горизонт": 211640,
    "Остиум (Армада люкс)": 282187,
    "Армада": 264550,
    "Акрополь": 176367
  }
};

function loadModels() {
  const category = document.getElementById("category").value;
  const modelSelect = document.getElementById("model");
  modelSelect.innerHTML = "";
  modelSelect.disabled = true;

  if (data[category]) {
    modelSelect.disabled = false;
    modelSelect.innerHTML = `<option value="">Выберите модель</option>`;
    Object.keys(data[category]).forEach(model => {
      modelSelect.innerHTML += `<option value="${model}">${model}</option>`;
    });
  }
  calculate();
}

// Restore calculator data on page load
document.addEventListener('DOMContentLoaded', function() {
  const savedData = localStorage.getItem('calculatorData');
  if (savedData) {
    const data = JSON.parse(savedData);
    
    if (data.category) {
      document.getElementById("category").value = data.category;
      loadModels();
      
      // Wait a bit for models to load, then set the model
      setTimeout(() => {
        if (data.model) {
          document.getElementById("model").value = data.model;
        }
        if (data.width) {
          document.getElementById("width").value = data.width;
        }
        if (data.height) {
          document.getElementById("height").value = data.height;
        }
        if (data.discount !== undefined) {
          document.getElementById("discount").value = data.discount;
        }
        if (data.installation) {
          document.getElementById("installation").value = data.installation;
        }
        if (data.thermalBreak !== undefined) {
          document.getElementById("thermalBreak").checked = data.thermalBreak;
        }
        calculate();
      }, 100);
    }
  }
});

function calculate() {
  const category = document.getElementById("category").value;
  const model = document.getElementById("model").value;
  const width = parseFloat(document.getElementById("width").value);
  const height = parseFloat(document.getElementById("height").value);
  const discount = parseFloat(document.getElementById("discount").value) || 0;
  const installationValue = parseInt(document.getElementById("installation").value);
  const hasInstallation = !isNaN(installationValue) && installationValue > 0;
  const hasThermalBreak = document.getElementById("thermalBreak").checked;
  const output = document.getElementById("output");

  // Save calculator data
  const calculatorData = {
    category: category,
    model: model,
    width: width,
    height: height,
    discount: discount,
    installation: installationValue,
    thermalBreak: hasThermalBreak
  };
  localStorage.setItem('calculatorData', JSON.stringify(calculatorData));

  if (data[category] && data[category][model] && width > 0 && height > 0) {
    const priceM2 = data[category][model];
    const area = width * height;
    const totalDoorOnly = Math.round(priceM2 * area);
    const discountedDoor = Math.round(totalDoorOnly * (1 - discount / 100));
    
    // Терморазрыв: 70,000 ₸ за м²
    const thermalBreakCost = hasThermalBreak ? Math.round(70000 * area) : 0;
    
    const doorWithThermalBreak = discountedDoor + thermalBreakCost;
    const finalTotal = hasInstallation ? doorWithThermalBreak + installationValue : doorWithThermalBreak;

    output.innerHTML = `
      <div><strong>Категория:</strong> ${category}</div>
      <div><strong>Модель:</strong> ${model}</div>
      <div><strong>Площадь двери:</strong> ${area.toFixed(2)} м²</div>
      <div><strong>Цена двери без скидки:</strong> ${totalDoorOnly.toLocaleString('ru-RU')} ₸</div>
      <div><strong>Скидка на дверь:</strong> ${discount}%</div>
      <div><strong>Цена двери со скидкой:</strong> ${discountedDoor.toLocaleString('ru-RU')} ₸</div>
      <div><strong>Терморазрыв:</strong> ${hasThermalBreak ? thermalBreakCost.toLocaleString('ru-RU') + ' ₸' : 'Нет'}</div>
      <div><strong>Установка:</strong> ${hasInstallation ? installationValue.toLocaleString('ru-RU') + ' ₸' : 'Нет'}</div>
      <div class="total-price"><strong>Итого:</strong> ${finalTotal.toLocaleString('ru-RU')} ₸</div>
    `;
    output.style.display = "block";
  } else {
    output.style.display = "none";
  }
}
</script> 