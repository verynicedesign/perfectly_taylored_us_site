(function () {
  var form = document.querySelector('.gate__form');
  if (!form) return;

  var inputs = form.querySelectorAll('.gate__input');
  var submit = form.querySelector('.gate__submit');

  function updateSubmitState() {
    var allFilled = true;
    inputs.forEach(function (input) {
      if (input.value.trim() === '') {
        allFilled = false;
      }
    });
    submit.disabled = !allFilled;
  }

  inputs.forEach(function (input) {
    input.addEventListener('blur', function () {
      input.value = input.value.trim();
      updateSubmitState();
    });
    input.addEventListener('input', updateSubmitState);
  });

  updateSubmitState();
})();
