document.addEventListener("DOMContentLoaded", function () {
  const imagePreviewInput = document.getElementById("image_preview_input");
  const preview = document.getElementById("image_preview");
  const imagePreviewSubmit = document.getElementById("image_preview_submit");

  if (!(imagePreviewInput && preview)) return;

  imagePreviewInput.style.display = "none";
  imagePreviewSubmit.style.display = "none";

  preview.addEventListener("click", function () {
    imagePreviewInput.click();
  });

  imagePreviewInput.addEventListener("change", function (event) {
    const file = event.target.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = function (e) {
        document.getElementById("image_preview").src = e.target.result;
        imagePreviewSubmit.style.display = "block";
      };
      reader.readAsDataURL(file);
    }
  });
});

document.addEventListener('DOMContentLoaded', () => {
  const button = document.getElementById('searchBtn');

  if (!button) return;

  button.addEventListener('click', (event) => {
    event.preventDefault();
    const termo = document.getElementById('search').value;

    if (termo === null || termo === '') {
      const errorMessage = document.getElementById('error');
      errorMessage.hidden = false;
      return;
    }

    document.getElementById('error').hidden = true;

    fetch('/vaccines/findByName', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
        'Accept': 'application/json'
      },
      body: new URLSearchParams({ name: termo })
    })
      .then(response => response.json())
      .then(data => renderVaccines(data.vaccines))
      .catch(error => alert('Erro:', error));

  });
});

function renderVaccines(vaccines) {
  const tbody = document.getElementById('vaccinesTableBody');
  if (!tbody) return;

  if (!vaccines || vaccines.length === 0) {
    tbody.innerHTML = '<tr><td colspan="3" class="border p-2 text-center">Nenhuma vacina encontrada.</td></tr>';
    return;
  }

  tbody.innerHTML = vaccines.map(vaccine => `
    <tr class="border">
      <td class="border p-2">${vaccine.id}</td>
      <td class="border p-2">${vaccine.name}</td>
      <td class="border p-2 hidden md:table-cell">${vaccine.description || 'Não informado'}</td>
    </tr>
  `).join('');
}

function openPage(evt, pageName) {
  var i, tabcontent, tablinks;

  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].classList.remove("active");
  }

  tablinks = document.getElementsByClassName("tablinks");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].classList.remove("active");
  }

  document.getElementById(pageName).classList.add("active");
  evt.currentTarget.classList.add("active");
}

function formatMoneyInput(input, finalize) {
  if (!input) return;

  let value = input.value || '';
  value = value.replace(/[^\d,]/g, '');

  if (value === '') {
    input.value = '';
    return;
  }

  const parts = value.split(',');
  let intPart = parts.shift() || '0';
  let decPart = parts.join('');

  intPart = intPart.replace(/^0+(?=\d)/, '') || '0';
  if (decPart.length > 2) decPart = decPart.slice(0, 2);

  const intWithThousands = intPart.replace(/\B(?=(\d{3})+(?!\d))/g, '.');

  if (finalize) {
    decPart = (decPart + '00').slice(0, 2);
    input.value = intWithThousands + ',' + decPart;
    return;
  }

  input.value = decPart ? intWithThousands + ',' + decPart : intWithThousands;
}

document.addEventListener("DOMContentLoaded", function () {

  const saleDateInput = document.getElementById('sale_date');
  const saleValueInput = document.getElementById('sale_value_in_cents');
  const soldSection = document.getElementById('sold-section');

  const stateSelect = document.getElementById('state');
  const deathSection = document.getElementById('death-section');
  const deathDateInput = document.getElementById('death_date');
  const deathReasonInput = document.getElementById('death_reason');

  if (!stateSelect) return;

  function updateDeathSection() {
    if (stateSelect.value === 'dead') {
      deathSection.classList.remove('hidden');
      deathDateInput.setAttribute('name', 'cattle[death_date]');
      deathReasonInput.setAttribute('name', 'cattle[death_reason]');
    } else {
      deathSection.classList.add('hidden');
      deathDateInput.removeAttribute('name');
      deathReasonInput.removeAttribute('name');
      deathDateInput.value = '';
      deathReasonInput.value = '';
    }
  }

  stateSelect.addEventListener('change', updateDeathSection);
  updateDeathSection();

  function updateSoldSection() {
    if (stateSelect.value === 'sold') {
      soldSection.classList.remove('hidden');
      saleDateInput.setAttribute('name', 'cattle[sale_date]');
      saleValueInput.setAttribute('name', 'cattle[sale_value_in_cents]');
    } else {
      soldSection.classList.add('hidden');
      saleDateInput.removeAttribute('name');
      saleValueInput.removeAttribute('name');
      saleDateInput.value = '';
      saleValueInput.value = '';
    }
  }

  stateSelect.addEventListener('change', updateSoldSection);
  updateSoldSection();
});