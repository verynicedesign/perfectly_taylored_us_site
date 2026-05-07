/* =========================================================================
   PERFECTLY TAYLORED — gate.js
   Production gate logic. Submits via fetch() to auth.php.
   Keeps localStorage for the welcome-screen animation only (not real auth).
   ========================================================================= */
(function () {
  var STORAGE_KEY       = 'pt-gate-welcome'; /* UI only — real auth is PHP session */
  var HELP_FORM_ENDPOINT = 'https://api.web3forms.com/submit';
  var HELP_FORM_KEY      = '77293868-441e-4869-aac0-eebdec7f398d';

  /* Elements */
  var gate        = document.getElementById('ptGate');
  var gateMain    = document.getElementById('ptGateMain');
  var gateWelcome = document.getElementById('ptGateWelcome');
  var gateForm    = document.getElementById('ptGateForm');
  var gateFirst   = document.getElementById('ptGateFirst');
  var gateLast    = document.getElementById('ptGateLast');
  var gatePass    = document.getElementById('ptGatePass');
  var gateHint    = document.getElementById('ptGateHint');
  var gateSubmit  = document.getElementById('ptGateSubmit');
  var gateHintBtn = document.getElementById('ptGateHintBtn');
  var gateTip     = document.getElementById('ptGateTip');
  var gateLost    = document.getElementById('ptGateLost');
  var welcomeName = document.getElementById('ptGateWelcomeName');
  var help        = document.getElementById('ptHelp');
  var helpScrim   = document.getElementById('ptHelpScrim');
  var helpClose   = document.getElementById('ptHelpClose');
  var helpCancel  = document.getElementById('ptHelpCancel');
  var helpFormEl  = document.getElementById('ptHelpFormEl');
  var helpName    = document.getElementById('ptHelpName');
  var helpPhone   = document.getElementById('ptHelpPhone');
  var helpHint    = document.getElementById('ptHelpHint');
  var helpSubmit  = document.getElementById('ptHelpSubmit');
  var helpFormDiv = document.getElementById('ptHelpForm');
  var helpSent    = document.getElementById('ptHelpSent');
  var helpDone    = document.getElementById('ptHelpDone');

  if (!gate) return;

  /* ---- Welcome animation (UI only) ---- */
  function readWelcome() {
    try {
      var raw = localStorage.getItem(STORAGE_KEY);
      if (!raw) return null;
      var v = JSON.parse(raw);
      if (!v || !v.exp || v.exp < Date.now()) return null;
      return v;
    } catch (e) { return null; }
  }

  function unlock() {
    gate.classList.add('is-leaving');
    setTimeout(function () { window.location.href = 'home.php'; }, 800);
  }

  function showWelcome(first) {
    welcomeName.textContent = first.toLowerCase() + '.';
    gateMain.classList.add('is-hidden');
    gateWelcome.classList.remove('is-hidden');
    setTimeout(unlock, 2200);
  }

  /* If returning visitor (PHP session already valid, stored name exists) */
  var stored = readWelcome();
  if (stored && stored.first) {
    showWelcome(stored.first);
  } else {
    setTimeout(function () { gateFirst && gateFirst.focus({ preventScroll: true }); }, 600);
  }

  /* ---- Form submit — posts to auth.php via fetch ---- */
  gateForm.addEventListener('submit', function (e) {
    e.preventDefault();
    var f = gateFirst.value.trim();
    var l = gateLast.value.trim();
    var p = gatePass.value.trim().toLowerCase().replace(/\s+/g, ' ');

    if (!f || !l) {
      showHint('Please add your first and last name.', true);
      return;
    }
    if (!p) {
      showHint('Please enter your password.', true);
      return;
    }

    gate.classList.add('is-submitting');
    gateSubmit.disabled = true;
    gateSubmit.textContent = 'Opening\u2026';
    gateHint.innerHTML = '';

    var body = new URLSearchParams();
    body.append('first_name', f);
    body.append('last_name', l);
    body.append('password', p);

    fetch('auth.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: body.toString(),
      redirect: 'manual'
    })
    .then(function (res) {
      /* auth.php returns 302 to home.php on success, 302 to index.php?err=1 on fail */
      var dest = res.headers.get('location') || res.url;
      if (res.type === 'opaqueredirect' || (dest && dest.indexOf('home.php') !== -1)) {
        /* Success — store name for welcome animation, then navigate */
        try {
          localStorage.setItem(STORAGE_KEY, JSON.stringify({
            first: f, exp: Date.now() + 3600 * 1000 /* 1 hour, just for the animation */
          }));
        } catch (e) {}
        showWelcome(f);
      } else {
        /* Failed */
        gate.classList.remove('is-submitting');
        gateSubmit.disabled = false;
        gateSubmit.textContent = 'Enter \u2192';
        gateHint.innerHTML = 'That doesn\'t match \u2014 <button class="pt-gate__help-inline" id="ptInlineHelp">get help</button>.';
        var inlineHelp = document.getElementById('ptInlineHelp');
        if (inlineHelp) inlineHelp.addEventListener('click', openHelp);
      }
    })
    .catch(function () {
      gate.classList.remove('is-submitting');
      gateSubmit.disabled = false;
      gateSubmit.textContent = 'Enter \u2192';
      showHint('Something went wrong. Please try again.', true);
    });
  });

  function showHint(msg, isError) {
    gateHint.textContent = msg;
    gateHint.classList.toggle('is-error', !!isError);
  }

  /* Clear error on input */
  [gateFirst, gateLast, gatePass].forEach(function (el) {
    el.addEventListener('input', function () { gateHint.textContent = ''; gateHint.innerHTML = ''; });
  });

  /* ---- Password hint tooltip ---- */
  var tipOpen = false;
  gateHintBtn.addEventListener('click', function (e) {
    e.stopPropagation();
    tipOpen = !tipOpen;
    gateTip.classList.toggle('is-hidden', !tipOpen);
  });
  document.addEventListener('click', function (e) {
    if (tipOpen && !gateTip.contains(e.target) && e.target !== gateHintBtn) {
      tipOpen = false;
      gateTip.classList.add('is-hidden');
    }
  });
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && tipOpen) { tipOpen = false; gateTip.classList.add('is-hidden'); }
  });

  /* ---- Help modal ---- */
  function openHelp(e) {
    if (e) e.preventDefault();
    help.classList.remove('is-hidden');
    var f = gateFirst.value.trim();
    var l = gateLast.value.trim();
    if (f || l) helpName.value = (f + ' ' + l).trim();
    setTimeout(function () { (helpName.value ? helpPhone : helpName).focus({ preventScroll: true }); }, 250);
  }

  function closeHelp() {
    help.classList.add('is-hidden');
    helpHint.textContent = '';
    helpName.value = '';
    helpPhone.value = '';
    helpFormDiv.classList.remove('is-hidden');
    helpSent.classList.add('is-hidden');
    helpSubmit.disabled = false;
    helpSubmit.textContent = 'Send request \u2192';
  }

  gateLost.addEventListener('click', openHelp);
  helpClose.addEventListener('click', closeHelp);
  helpCancel.addEventListener('click', closeHelp);
  helpScrim.addEventListener('click', closeHelp);
  if (helpDone) helpDone.addEventListener('click', closeHelp);

  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && !help.classList.contains('is-hidden')) closeHelp();
  });

  helpFormEl.addEventListener('submit', function (e) {
    e.preventDefault();
    var n = helpName.value.trim();
    var p = helpPhone.value.trim();
    if (!n || !p) { helpHint.textContent = 'Please add your name and phone number.'; return; }
    helpSubmit.disabled = true;
    helpSubmit.textContent = 'Sending\u2026';
    helpHint.textContent = '';

    fetch(HELP_FORM_ENDPOINT, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', Accept: 'application/json' },
      body: JSON.stringify({
        access_key: HELP_FORM_KEY,
        subject: 'Wedding site \u2014 password help request',
        from_name: 'perfectly taylored gate',
        guest_name: n,
        guest_phone: p
      })
    }).then(function (r) {
      if (!r.ok) throw new Error('Network');
      helpFormDiv.classList.add('is-hidden');
      helpSent.classList.remove('is-hidden');
    }).catch(function () {
      helpHint.textContent = "Couldn't send. Please try again.";
      helpSubmit.disabled = false;
      helpSubmit.textContent = 'Send request \u2192';
    });
  });
})();
