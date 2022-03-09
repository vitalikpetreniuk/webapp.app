<div id="modal-revenue" class="modal d-flex flex-column">
    <span class="modal__title">Revenue</span>
    <form id="revenueForm" name="revenueForm" class="drag-drop d-flex flex-column" action="{{ route('revenues.val') }}"
          method="POST"
          enctype="multipart/form-data">
        @csrf
        <div class="drag-drop__input d-flex flex-column align-items-center form-group">
            <input class="drag-drop__file" type="file" name="files" id="revenueFile"
                   data-multiple-caption="{count} files selected" accept=".xls, .xlsx"/>
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
        <input type="submit" value="Отправить">
    </form>
    <a href="{{ asset('xlsx/sample.xlsx') }}" type="download" download="Sample.xlsx" class="btn__modal turquoise mt-30">Download
        Sample</a>
    <div class="modal__close">
        <img src="{{ asset('frontend/images/dist/icons/x.svg') }}" alt="x">
    </div>
</div>
{!! $validator->selector('#revenueForm') !!}
<script>
    jQuery(function ($) {
        $('#revenueForm').on('submit', function (e) {
            e.preventDefault();
            let formdata = new FormData(document.querySelector('#revenueForm'))
            $.ajax({
                "url": "{{ route('revenues.store') }}",
                "type": "POST",
                "processData": false,
                "contentType": false,
                "data": formdata,
                success: function (response) {
                    let result = JSON.parse(response)
                    $('#revenueForm').append(`<p>${result.message}</p>`);
                    console.log(result)
                    alert(result.message)
                },
                error: function (response) {
                    let result = JSON.parse(response)
                    $('#revenueForm').append(`<span class="help-block error-help-block">${result.message}</span>`);
                    console.log(result)
                    alert(result.message)
                },
            });
        });
    })
</script>
