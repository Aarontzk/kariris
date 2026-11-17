// KARIRIS — interaksi UI tanpa framework (popup, tab, konfirmasi, toast, bottom sheet)
(() => {
  const $ = (sel, root = document) => root.querySelector(sel);
  const $$ = (sel, root = document) => [...root.querySelectorAll(sel)];

  const open = (el) => { if (el) { el.classList.add('is-open'); document.body.style.overflow = 'hidden'; } };
  const close = (el) => {
    if (!el) return;
    el.classList.remove('is-open');
    if (!$('.overlay.is-open, .sheet-wrap.is-open')) document.body.style.overflow = '';
  };

  // Buka popup: <button data-open="id">
  document.addEventListener('click', (e) => {
    const opener = e.target.closest('[data-open]');
    if (opener) { e.preventDefault(); prepareForm(opener); open(document.getElementById(opener.dataset.open)); return; }

    const closer = e.target.closest('[data-close]');
    if (closer) { close(closer.closest('.overlay, .sheet-wrap')); return; }

    if (e.target.matches('.overlay, .sheet-wrap')) close(e.target);

    const kebab = e.target.closest('[data-menu]');
    $$('.menu.is-open').forEach((m) => { if (!kebab || m !== kebab.nextElementSibling) m.classList.remove('is-open'); });
    if (kebab) kebab.nextElementSibling.classList.toggle('is-open');

    const toggle = e.target.closest('[data-expand]');
    if (toggle) {
      const card = toggle.closest('.ecard, .review');
      card.classList.toggle('is-open');
      if (toggle.dataset.expand === 'text') toggle.textContent = card.classList.contains('is-open') ? 'Read Less' : 'Read More';
    }
  });

  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') $$('.overlay.is-open, .sheet-wrap.is-open').forEach(close);
  });

  // Isi form popup untuk mode tambah/edit. data-form="formId" data-action="url" data-method="PUT" data-values='{...}'
  function prepareForm(opener) {
    const form = opener.dataset.form && document.getElementById(opener.dataset.form);
    if (!form) return;
    form.reset();
    form.action = opener.dataset.action || form.dataset.defaultAction;
    $('[name="_method"]', form).value = opener.dataset.method || 'POST';
    const title = $('[data-form-title]', form.closest('.modal'));
    if (title && opener.dataset.title) title.textContent = opener.dataset.title;
    const values = opener.dataset.values ? JSON.parse(opener.dataset.values) : {};
    Object.entries(values).forEach(([k, v]) => { const f = form.elements[k]; if (f) f.value = v ?? ''; });
  }

  // Tab: <div data-tabs> <button data-tab="x"> ... <section data-panel="x">
  $$('[data-tabs]').forEach((group) => {
    const scope = group.parentElement;
    group.addEventListener('click', (e) => {
      const btn = e.target.closest('[data-tab]');
      if (!btn) return;
      $$('[data-tab]', group).forEach((b) => b.classList.toggle('is-active', b === btn));
      $$('[data-panel]', scope).forEach((p) => p.classList.toggle('is-active', p.dataset.panel === btn.dataset.tab));
      const url = new URL(location.href);
      url.searchParams.set('tab', btn.dataset.tab);
      history.replaceState(null, '', url);
    });
  });

  // Konfirmasi sebelum submit: <form data-confirm="warn|danger" data-confirm-title data-confirm-text data-confirm-ok>
  const dialog = $('#confirm');
  let pending = null;
  document.addEventListener('submit', (e) => {
    const form = e.target;
    if (!form.dataset.confirm || form.dataset.confirmed) return;
    e.preventDefault();
    if (!form.reportValidity()) return;
    pending = form;
    const danger = form.dataset.confirm === 'danger';
    $('[data-confirm-head]', dialog).className = 'modal__head ' + (danger ? 'modal__head--danger' : 'modal__head--warn');
    $('[data-confirm-title]', dialog).textContent = form.dataset.confirmTitle || 'Save Changes?';
    $('[data-confirm-text]', dialog).textContent = form.dataset.confirmText || 'Are you sure you want to save these changes?';
    const ok = $('[data-confirm-ok]', dialog);
    ok.textContent = form.dataset.confirmOk || 'Save';
    ok.className = 'btn ' + (danger ? 'btn--danger' : 'btn--warn');
    open(dialog);
  });
  $('[data-confirm-ok]', dialog)?.addEventListener('click', () => {
    if (!pending) return;
    pending.dataset.confirmed = '1';
    pending.submit();
  });

  // Toast
  const toast = $('[data-toast]');
  if (toast) {
    requestAnimationFrame(() => toast.classList.add('is-show'));
    setTimeout(() => toast.classList.remove('is-show'), 2600);
  }

  // Career match: pilih lingkaran
  $$('[data-cm]').forEach((circle) => circle.addEventListener('click', () => {
    $$('[data-cm]').forEach((c) => c.classList.toggle('is-active', c === circle));
    $$('[data-cm-detail]').forEach((d) => d.classList.toggle('is-active', d.dataset.cmDetail === circle.dataset.cm));
  }));

  // Bottom sheet Day in the Life: <button data-sheet='{"nama":..}'>
  const sheet = $('#sheet');
  document.addEventListener('click', (e) => {
    const btn = e.target.closest('[data-sheet]');
    if (!btn || !sheet) return;
    const d = JSON.parse(btn.dataset.sheet);
    $('[data-sheet-name]', sheet).textContent = d.nama;
    $('[data-sheet-sub]', sheet).textContent = d.sub;
    $('[data-sheet-desc]', sheet).textContent = d.deskripsi;
    $('[data-sheet-icon]', sheet).innerHTML = $('.acard__ic', btn).innerHTML;
    const chips = $('[data-sheet-chips]', sheet);
    chips.replaceChildren(...d.chips.map((t) => Object.assign(document.createElement('span'), { textContent: t })));
    open(sheet);
  });

  // Buka popup otomatis jika ada error validasi
  const auto = $('[data-autopen]');
  if (auto) open(document.getElementById(auto.dataset.autopen));
})();
