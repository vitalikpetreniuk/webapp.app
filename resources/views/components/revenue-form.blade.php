<div id="modal-revenue" class="modal d-flex flex-column">
    <span class="modal__title">Revenue</span>
    <form id="revenueF"
          method="POST"
          enctype="multipart/form-data"
          name="revenueF">
        @csrf
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
        <div class="modal__url d-flex align-items-center">
            <input disabled class="field-text align-self-end turquoise" type="text" placeholder="or paste URL adress">
            <button type="submit" href="#" class="turquoise">Submit</button>
        </div>
        <a href="{{ asset('xlsx/sample.xlsx') }}" class="btn__modal turquoise mt-30" download>Download Sample</a>
    </form>
    <div class="modal__close">
        <img src="{{ asset('frontend/images/dist/icons/x.svg') }}" alt="x">
    </div>
</div>
{!! $validator->selector('#revenueF') !!}
<script>
    jQuery(function ($) {
        let form = '#revenueF';
        $(form).on('submit', function (e) {
            e.preventDefault();
            let form = '#revenueF';
            let formdata = new FormData(document.querySelector(form))
            $.ajax({
                "url": "{{ route('revenues.store') }}",
                "type": "POST",
                "processData": false,
                "contentType": false,
                "data": formdata,
                success: function (response) {
                    let result = response.responseJSON ? response.responseJSON : JSON.parse(response);
                    if (!result.success) {
                        revenueErrorCatch(response)
                        return;
                    }
                    document.querySelector(form).reset();

                    $('#modal-revenue').find(`.drag-drop__error`).removeClass('show')
                    $('#modal-revenue').find('.drag-drop__success').text(result.message).addClass('show');

                    setTimeout(()=>$('#modal-revenue').find(`.drag-drop__success, .drag-drop__error`).removeClass('show'), 5000);
                },
                error: revenueErrorCatch,
            });
        });
    })

    function revenueErrorCatch(response) {
        let result = response.responseJSON ? response.responseJSON : JSON.parse(response);
        $('#revenueForm').append(`<span class="help-block error-help-block">${result.message}</span>`);
        setTimeout(()=>$('#modal-revenue').find(`.drag-drop__success, .drag-drop__error`).removeClass('show'), 5000);
    }
</script>
