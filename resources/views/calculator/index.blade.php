@extends('layouts.admin')

@section('title', 'Калькулятор дверей')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="calculator-container">
                <div class="page-header">
                    <div class="header-content">
                        <div class="header-icon">
                            <i class="fas fa-calculator"></i>
                        </div>
                        <div class="header-text">
                            <h1 class="page-title">Калькулятор дверей</h1>
                            <p class="page-subtitle">Рассчитайте стоимость двери MDS Doors</p>
                        </div>
                    </div>
                </div>

                <div class="calculator-section">
                    <div class="calculator-card">
                        <!-- ЛОГОТИП 
                        <div class="logo-container">
                            <img src="{{ asset('images/logo.png') }}" alt="MDS Doors" class="logo">
                        </div>-->

                        <form id="calculatorForm" class="calculator-form" autocomplete="off">
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="category" class="form-label">
                                        <i class="fas fa-tag"></i>
                                        Категория двери
                                    </label>
                                    <select id="category" class="form-control" onchange="loadModels()">
                                        <option value="">— Выберите категорию —</option>
                                        <option value="Lux">Lux</option>
                                        <option value="Premium">Premium</option>
                                        <option value="Comfort">Comfort</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="model" class="form-label">
                                        <i class="fas fa-door-open"></i>
                                        Модель
                                    </label>
                                    <select id="model" class="form-control" disabled>
                                        <option value="">— Выберите модель —</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="height" class="form-label">
                                        <i class="fas fa-arrows-alt-v"></i>
                                        Высота двери (м)
                                    </label>
                                    <input type="number" id="height" class="form-control" step="0.01" placeholder="Например: 2.40">
                                </div>

                                <div class="form-group">
                                    <label for="width" class="form-label">
                                        <i class="fas fa-arrows-alt-h"></i>
                                        Ширина двери (м)
                                    </label>
                                    <input type="number" id="width" class="form-control" step="0.01" placeholder="Например: 1.80">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="discount" class="form-label">
                                        <i class="fas fa-percentage"></i>
                                        Скидка (% только на дверь)
                                    </label>
                                    <input type="number" id="discount" class="form-control" step="1" value="0" min="0" max="100" oninput="calculate()">
                                </div>

                                <div class="form-group">
                                    <label for="thermobreak" class="form-label">
                                        <i class="fas fa-snowflake"></i>
                                        Терморазрыв
                                    </label>
                                    <div class="checkbox-container">
                                        <input type="checkbox" id="thermobreak" class="form-checkbox" onchange="calculate()">
                                        <label for="thermobreak" class="checkbox-label">Добавить терморазрыв (70 000 ₸/м²)</label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="installation" class="form-label">
                                    <i class="fas fa-tools"></i>
                                    Установка (₸)
                                </label>
                                <input type="number" id="installation" class="form-control" step="100" placeholder="Например: 10000">
                            </div>

                            <button type="button" class="btn btn-calculate" onclick="calculate()">
                                <i class="fas fa-calculator"></i>
                                Рассчитать стоимость
                            </button>
                        </form>

                        <div class="result-container" id="output" style="display:none;">
                            <div class="result-header">
                                <div class="result-header-left">
                                    <i class="fas fa-receipt"></i>
                                    <span>Результат расчета</span>
                                </div>
                                <button type="button" class="btn-copy" onclick="copyResults()">
                                    <i class="fas fa-copy"></i>
                                    Копировать
                                </button>
                            </div>
                            <div class="result-content" id="resultContent"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.calculator-container {
    max-width: 800px;
    margin: 0 auto;
    padding: 24px;
    scroll-behavior: auto;
}

/* Предотвращаем автоматическую прокрутку */
html {
    scroll-behavior: auto !important;
}

body {
    scroll-behavior: auto !important;
}

.page-header {
    margin-bottom: 32px;
    padding-bottom: 24px;
    border-bottom: 1px solid var(--border-color);
}

.header-content {
    display: flex;
    align-items: center;
    gap: 16px;
}

.header-icon {
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, #1ba4e9 0%, #ac76e3 100%);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 20px;
}

.page-title {
    font-size: 28px;
    font-weight: 700;
    color: var(--text-primary);
    margin: 0;
}

.page-subtitle {
    font-size: 14px;
    color: var(--text-secondary);
    margin: 4px 0 0 0;
}

.calculator-section {
    background: var(--bg-card);
    border-radius: 16px;
    box-shadow: var(--shadow-lg);
    border: 1px solid var(--border-color);
    overflow: hidden;
}

.calculator-card {
    padding: 32px;
}

.logo-container {
    text-align: center;
    margin-bottom: 32px;
}

.logo {
    max-width: 160px;
    height: auto;
}

.calculator-form {
    display: flex;
    flex-direction: column;
    gap: 24px;
}

.form-group {
    position: relative;
}

.form-label {
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 600;
    font-size: 14px;
    color: var(--text-secondary);
    margin-bottom: 8px;
}

.form-label i {
    color: var(--accent-primary);
    font-size: 16px;
}

.form-control {
    width: 100%;
    padding: 12px 16px;
    border: 2px solid var(--border-color);
    border-radius: 8px;
    font-size: 14px;
    transition: all 0.2s ease;
    background: var(--bg-secondary);
    color: var(--text-primary);
}

.form-control:focus {
    outline: none;
    border-color: var(--accent-primary);
    background: var(--bg-primary);
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.15);
}

.form-control:disabled {
    background: var(--bg-tertiary);
    color: var(--text-muted);
    cursor: not-allowed;
}

.checkbox-container {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 16px;
    background: var(--bg-secondary);
    border: 2px solid var(--border-color);
    border-radius: 8px;
    transition: all 0.2s ease;
}

.checkbox-container:hover {
    border-color: var(--accent-primary);
    background: var(--bg-primary);
}

.form-checkbox {
    width: 18px;
    height: 18px;
    accent-color: var(--accent-primary);
    cursor: pointer;
}

.checkbox-label {
    font-size: 14px;
    color: var(--text-secondary);
    cursor: pointer;
    margin: 0;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
}

.btn-calculate {
    background: linear-gradient(135deg, #1ba4e9 0%, #ac76e3 100%);
    color: white;
    border: none;
    padding: 16px 24px;
    border-radius: 8px;
    font-weight: 600;
    font-size: 16px;
    cursor: pointer;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    box-shadow: 0 2px 4px rgba(102, 126, 234, 0.2);
}

.btn-calculate:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(102, 126, 234, 0.3);
}

.result-container {
    margin-top: 32px;
    background: linear-gradient(135deg, rgba(16, 185, 129, 0.12) 0%, rgba(16, 185, 129, 0.08) 100%);
    border: 1px solid rgba(16, 185, 129, 0.25);
    border-radius: 12px;
    overflow: hidden;
    scroll-behavior: auto;
    scroll-margin-top: 0;
}

.result-header {
    background: #28a745;
    color: white;
    padding: 16px 24px;
    font-weight: 600;
    font-size: 16px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
}

.result-header-left { display: flex; align-items: center; gap: 8px; }

.btn-copy {
    background: rgba(255,255,255,0.15);
    color: #fff;
    border: 1px solid rgba(255,255,255,0.3);
    padding: 8px 12px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    cursor: pointer;
    transition: all 0.2s ease;
}

.btn-copy:hover { background: rgba(255,255,255,0.25); transform: translateY(-1px); }
.result-content {
    padding: 24px;
    color: rgba(15, 87, 50, 0.95);
    font-size: 14px;
    line-height: 1.6;
}

.result-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 0;
    border-bottom: 1px solid rgba(15, 87, 36, 0.2);
}

.result-item:last-child {
    border-bottom: none;
    font-weight: 700;
    font-size: 18px;
    color: rgba(15, 83, 45, 0.95);
}

.result-label {
    font-weight: 600;
}

.result-value {
    text-align: right;
}

/* Анимации */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.calculator-section {
    animation: fadeIn 0.3s ease-out;
}

/* Адаптивность */
@media (max-width: 768px) {
    .calculator-container {
        padding: 16px;
    }
    
    .calculator-card {
        padding: 24px;
    }
    
    .form-row {
        grid-template-columns: 1fr;
    }
}

.dark-mode .result-container {
    background: linear-gradient(135deg, rgba(16, 185, 129, 0.25) 0%, rgba(16, 185, 129, 0.15) 100%);
    border-color: rgba(16, 185, 129, 0.4);
}

.dark-mode .result-content {
    color: rgba(209, 233, 221, 0.95);
}

.dark-mode .result-item {
    border-bottom-color: rgba(209, 233, 221, 0.2);
}

.dark-mode .result-item:last-child {
    color: rgba(129, 230, 169, 0.95);
}
</style>

<script>
const data = {
  "Lux": {
    "Англия": 490566,
    "Империал": 490566,
    "Бастион / Хайтек": 528302,
    "Фьюжн / Хайтек": 462264,
    "Фьюжн сложный": 566038,
    "Лион": 433962,
    "Агора": 358491,
    "Ажур": 367925,
    "Горизонт": 358491,
    "Остиум (Армада люкс)": 433962,
    "Армада": 415094,
    "Акрополь": 311321,
    "Эксклюзив": 716981
  },
  "Premium": {
    "Англия": 433962,
    "Империал": 433962,
    "Бастион / Хайтек": 471698,
    "Фьюжн / Хайтек": 405660,
    "Фьюжн сложный": 509434,
    "Лион": 377358,
    "Агора": 301887,
    "Ажур": 311321,
    "Горизонт": 301887,
    "Остиум (Армада люкс)": 377358,
    "Армада": 358491,
    "Акрополь": 254717
  },
  "Comfort": {
    "Англия": 358491,
    "Империал": 358491,
    "Бастион / Хайтек": 396226,
    "Фьюжн / Хайтек": 358491,
    "Лион": 301887,
    "Агора": 226415,
    "Ажур": 235849,
    "Горизонт": 226415,
    "Остиум (Армада люкс)": 301887,
    "Армада": 283019,
    "Акрополь": 188679
  }
};

function loadModels() {
  const category = document.getElementById("category").value;
  const modelSelect = document.getElementById("model");
  const thermobreakContainer = document.querySelector('.form-group:has(#thermobreak)');
  
  modelSelect.innerHTML = "";
  modelSelect.disabled = true;

  // Скрываем/показываем терморазрыв в зависимости от категории
  if (category === "Comfort") {
    thermobreakContainer.style.display = "none";
    document.getElementById("thermobreak").checked = false;
  } else {
    thermobreakContainer.style.display = "block";
  }

  if (data[category]) {
    modelSelect.disabled = false;
    modelSelect.innerHTML = `<option value="">— Выберите модель —</option>`;
    Object.keys(data[category]).forEach(model => {
      modelSelect.innerHTML += `<option value="${model}">${model}</option>`;
    });
  }
  // Убираем автоматический вызов calculate() при смене категории
}

function calculate() {
  const category = document.getElementById("category").value;
  const model = document.getElementById("model").value;
  const width = parseFloat(document.getElementById("width").value);
  const height = parseFloat(document.getElementById("height").value);
  const discount = parseFloat(document.getElementById("discount").value) || 0;
  const installationValue = parseInt(document.getElementById("installation").value);
  const hasInstallation = !isNaN(installationValue) && installationValue > 0;
  const hasThermobreak = document.getElementById("thermobreak").checked;
  const output = document.getElementById("output");
  const resultContent = document.getElementById("resultContent");

  if (data[category] && data[category][model] && width > 0 && height > 0) {
    const priceM2 = data[category][model];
    const area = width * height;
    const totalDoorOnly = Math.round(priceM2 * area);
    const discountedDoor = Math.round(totalDoorOnly * (1 - discount / 100));
    
    // Расчет терморазрыва
    const thermobreakCost = hasThermobreak ? Math.round(70000 * area) : 0;
    
    const finalTotal = discountedDoor + thermobreakCost + (hasInstallation ? installationValue : 0);

    resultContent.innerHTML = `
      <div class="result-item">
        <span class="result-label">Категория:</span>
        <span class="result-value">${category}</span>
      </div>
      <div class="result-item">
        <span class="result-label">Модель:</span>
        <span class="result-value">${model}</span>
      </div>
      <div class="result-item">
        <span class="result-label">Площадь двери:</span>
        <span class="result-value">${area.toFixed(2)} м²</span>
      </div>
      <div class="result-item">
        <span class="result-label">Цена двери без скидки:</span>
        <span class="result-value">${totalDoorOnly.toLocaleString('ru-RU')} ₸</span>
      </div>
      <div class="result-item">
        <span class="result-label">Скидка на дверь:</span>
        <span class="result-value">${discount}%</span>
      </div>
      <div class="result-item">
        <span class="result-label">Цена двери со скидкой:</span>
        <span class="result-value">${discountedDoor.toLocaleString('ru-RU')} ₸</span>
      </div>
      ${hasThermobreak ? `
      <div class="result-item">
        <span class="result-label">Терморазрыв:</span>
        <span class="result-value">${thermobreakCost.toLocaleString('ru-RU')} ₸</span>
      </div>
      ` : ''}
      <div class="result-item">
        <span class="result-label">Установка:</span>
        <span class="result-value">${hasInstallation ? installationValue.toLocaleString('ru-RU') + ' ₸' : 'Нет'}</span>
      </div>
      <div class="result-item">
        <span class="result-label">Итого:</span>
        <span class="result-value">${finalTotal.toLocaleString('ru-RU')} ₸</span>
      </div>
    `;
    output.style.display = "block";
    // Предотвращаем автоматическую прокрутку к результатам
    output.scrollIntoView = function() { return false; };
  } else {
    output.style.display = "none";
  }
}

function copyResults() {
  const output = document.getElementById('output');
  const resultContent = document.getElementById('resultContent');
  if (!output || output.style.display === 'none') { return; }

  // Сформируем читаемый текст из текущего результата
  const lines = [];
  const items = resultContent.querySelectorAll('.result-item');
  items.forEach((item) => {
    const label = item.querySelector('.result-label')?.textContent?.trim() || '';
    const value = item.querySelector('.result-value')?.textContent?.trim() || '';
    if (label || value) {
      lines.push(`${label} ${value}`.trim());
    }
  });

  const textToCopy = lines.join('\n');

  if (navigator.clipboard && window.isSecureContext) {
    navigator.clipboard.writeText(textToCopy).then(() => {
      showCopyToast();
    }).catch(() => fallbackCopy(textToCopy));
  } else {
    fallbackCopy(textToCopy);
  }
}

function fallbackCopy(text) {
  const textarea = document.createElement('textarea');
  textarea.value = text;
  textarea.setAttribute('readonly', '');
  textarea.style.position = 'absolute';
  textarea.style.left = '-9999px';
  document.body.appendChild(textarea);
  textarea.select();
  document.execCommand('copy');
  document.body.removeChild(textarea);
  showCopyToast();
}

function showCopyToast() {
  const toast = document.createElement('div');
  toast.textContent = 'Скопировано';
  toast.style.position = 'fixed';
  toast.style.right = '20px';
  toast.style.bottom = '20px';
  toast.style.background = 'rgba(40,167,69,0.95)';
  toast.style.color = '#fff';
  toast.style.padding = '10px 14px';
  toast.style.borderRadius = '8px';
  toast.style.fontSize = '12px';
  toast.style.boxShadow = '0 4px 12px rgba(0,0,0,0.15)';
  toast.style.zIndex = '9999';
  document.body.appendChild(toast);
  setTimeout(() => { toast.remove(); }, 1500);
}

// Предотвращаем автоматическую прокрутку при загрузке страницы
document.addEventListener('DOMContentLoaded', function() {
  // Устанавливаем позицию прокрутки в начало страницы
  window.scrollTo(0, 0);
  
  // Предотвращаем автоматическую прокрутку к элементам с id
  const preventAutoScroll = function(e) {
    if (e.target.id && e.target.scrollIntoView) {
      e.preventDefault();
      e.target.scrollIntoView = function() { return false; };
    }
  };
  
  document.addEventListener('focus', preventAutoScroll, true);
});
</script>
@endsection
