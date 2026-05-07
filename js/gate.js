(function () {
  var form    = document.getElementById('ptGateForm');
  if (!form) return;

  var inputs  = form.querySelectorAll('.pt-gate__input');
  var submit  = document.getElementById('ptGateSubmit');
  var hintBtn = document.getElementById('ptGateHintBtn');
  var tip     = document.getElementById('ptGateTip');
  var lostBtn = document.getElementById('ptGateLost');

  /* Enable submit only when all fields have values */
  function updateSubmit() {
    var allFilled = true;
    inputs.forEach(function (inp) { if (!inp.value.trim()) allFilled = false; });
    submit.disabled = !allFilled;
  }
  inputs.forEach(function (inp) {
    inp.addEventListener('input', updateSubmit);
    inp.addEventListener('blur', function () { inp.value = inp.value.trim(); updateSubmit(); });
  });
  updateSubmit();

  /* Password hint tooltip */
  if (hintBtn && tip) {
    hintBtn.addEventListener('click', function (e) {
      e.stopPropagation();
      tip.classList.toggle('is-hidden');
    });
    document.addEventListener('click', function () { tip.classList.add('is-hidden'); });
  }

  /* Lost password -- mailto fallback */
  if (lostBtn) {
    lostBtn.addEventListener('click', function () {
      window.location.href = 'mailto:yes@verynice.design?subject=Password%20help%20%E2%80%94%20Perfectly%20Taylored';
    });
  }
})();
