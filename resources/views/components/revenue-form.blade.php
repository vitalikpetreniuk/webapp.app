<div id="modal-revenue" class="modal d-flex flex-column">
    <span class="modal__title">Revenue</span>
    <form id="revenueF"
          method="POST"
          enctype="multipart/form-data"
          name="revenueF" onsubmit="formSubmit(event)">
        @csrf
        <div class="type1">
            <div id="revenueForm" class="drag-drop d-flex flex-column">
                <div class="cat1 default-shown">
                    <div class="drag-drop__input d-flex flex-column align-items-center">
                        <input class="drag-drop__file" type="file" name="files" id="revenueFile"
                               data-multiple-caption="{count} files selected" accept=".xls, .xlsx">
                        <div class="drag-drop__selected d-flex flex-column align-items-center">
                            <div class="drag-drop__close">
                                <img src="{{ asset('frontend/images/dist/icons/close.svg') }}" alt="close">
                            </div>
                            <div class="d-flex align-items-center justify-content-center">
                                <img src="{{ asset('frontend/images/dist/icons/doc.svg') }}" alt="doc">
                            </div>
                            <span class="filename"></span>
                        </div>
                        <label for="revenueFile" class="drag-drop__label d-flex justify-content-center">
                            <img src="{{ asset('frontend/images/dist/icons/add-doc.svg') }}" alt="add">
                            <span>Select file</span>
                        </label>
                        <!--			<button class="drag-drop__button" type="submit">Upload</button>-->
                    </div>
                </div>
                <div class="cat2">
                    <div class="form-group">
                        <input class="input-field" type="number" min="1" max="100" name="cost-of-good-sold">
                    </div>
                </div>
                <div class="drag-drop__success">Successfully sent!</div>
                <div class="drag-drop__error">Warning or error!</div>
            </div>
        </div>
        <div class="type2">
            <div class="form-group">
                <input class="input-field edited-amount" type="number" name="edited-amount">
            </div>
            <button type="submit" class="btn__modal turquoise mt-30">Submit</button>
        </div>
        <button type="submit" class="btn__modal mentol mt-30">Submit</button>
        <div class="type1">
            <a href="{{ asset('xlsx/sample.xlsx') }}" class="btn__modal turquoise mt-30" download>Download Sample</a>
        </div>
    </form>
    <div class="modal__close">
        <img src="{{ asset('frontend/images/dist/icons/x.svg') }}" alt="x">
    </div>
</div>
{!! $validator->selector('#revenueF') !!}
