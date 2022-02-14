<div id="modal-revenue" class="modal d-flex flex-column">
    <span class="modal__title">Revenue</span>
    <form id="revenueForm" class="drag-drop d-flex flex-column" action="">
        <div class="drag-drop__input d-flex flex-column align-items-center">
            <input class="drag-drop__file" type="file" name="files[]" id="revenueFile" data-multiple-caption="{count} files selected" multiple />
            <div class="drag-drop__selected d-flex flex-column align-items-center">
                <div class="drag-drop__close">
                    <img src="{{ asset('frontend/images/dist/icons/close.svg') }}" alt="close">
                </div>
                <div class="d-flex align-items-center justify-content-center">
                    <img src="{{ asset('frontend/images/dist/icons/doc.svg') }}" alt="doc">
                </div>
                <span>Document №1.docx</span>
            </div>
            <label for="revenueFile" class="drag-drop__label d-flex justify-content-center">
                <img src="{{ asset('frontend/images/dist/icons/add-doc.svg') }}" alt="add">
                <span>Select file</span>
            </label>
            <!--			<button class="drag-drop__button" type="submit">Upload</button>-->
        </div>
        <div class="drag-drop__uploading">Uploading…</div>
        <div class="drag-drop__success">Done!</div>
        <div class="drag-drop__error">Error! <span></span>.</div>
    </form>
    <button type="submit" class="btn__modal turquoise mt-30">Download Sample</button>
    <div class="modal__close">
        <img src="{{ asset('frontend/images/dist/icons/x.svg') }}" alt="x">
    </div>
</div>
<div id="modal-expenses" class="modal d-flex flex-column">
    <span class="modal__title">Expenses</span>
    <div class="modal__select select blue mb-15">
        <input class="select__input" type="hidden" name="">
        <div class="select__head">Expenses type</div>
        <ul class="select__list" style="display: none;">
            <li class="select__item">Summ  $ (USD)</li>
            <li class="select__item">% of Ad spend</li>
            <li class="select__item">% of net revenue</li>
        </ul>
    </div>
    <p class="modal__text">
        select the date range for which you want to try on data
    </p>
    <form id="expensesForm" class="drag-drop d-flex flex-column" action="">
        <div class="drag-drop__input d-flex flex-column align-items-center">
            <input class="drag-drop__file" type="file" name="files[]" id="expensesFile" data-multiple-caption="{count} files selected" multiple />
            <div class="drag-drop__selected d-flex flex-column align-items-center">
                <div class="drag-drop__close">
                    <img src="{{ asset('frontend/images/dist/icons/close.svg') }}" alt="close">
                </div>
                <div class="d-flex align-items-center justify-content-center">
                    <img src="{{ asset('frontend/images/dist/icons/doc.svg') }}" alt="doc">
                </div>
                <span>Document №1.docx</span>
            </div>
            <label for="expensesFile" class="drag-drop__label d-flex justify-content-center">
                <img src="{{ asset('frontend/images/dist/icons/add-doc.svg') }}" alt="add">
                <span>Select file</span>
            </label>
            <!--			<button class="drag-drop__button" type="submit">Upload</button>-->
        </div>
        <div class="drag-drop__uploading">Uploading…</div>
        <div class="drag-drop__success">Done!</div>
        <div class="drag-drop__error">Error! <span></span>.</div>
    </form>
    <button type="submit" class="btn__modal azure mt-30">Download Sample</button>
    <div class="modal__close">
        <img src="{{ asset('frontend/images/dist/icons/x.svg') }}" alt="x">
    </div>
</div>
