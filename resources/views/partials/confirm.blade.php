{{-- Dialog konfirmasi generik (Save Changes? / Delete This Entry? / Change Username?) --}}
<div class="overlay" id="confirm" role="dialog" aria-modal="true" aria-labelledby="confirm-title">
    <div class="modal">
        <div class="modal__head modal__head--warn" data-confirm-head>
            <h2 class="modal__title" id="confirm-title" data-confirm-title>Save Changes?</h2>
            <button type="button" class="modal__close" data-close aria-label="Tutup"><x-icon name="close" :size="16" /></button>
        </div>
        <div class="modal__body">
            <p class="modal__text" data-confirm-text>Are you sure you want to save these changes?</p>
        </div>
        <div class="modal__actions">
            <button type="button" class="btn btn--outline" data-close>Cancel</button>
            <button type="button" class="btn btn--warn" data-confirm-ok>Save</button>
        </div>
    </div>
</div>
