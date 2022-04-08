// // Import vendor jQuery plugin example
// import '~/app/libs/mmenu/dist/mmenu.js'

import flatpickr from "flatpickr";
import 'flot';
import monthSelectPlugin from "flatpickr/dist/plugins/monthSelect/index.js";
import autoComplete from "@tarekraafat/autocomplete.js/dist/autoComplete.min.js";

jQuery(function ($) {

	// const $form = $('.drag-drop');
	const $revenueForm = $('#revenueForm')
	const $revenueFile = document.querySelector('#revenueFile')
	const $revenueSelected = document.querySelector('#revenueForm .drag-drop__selected')
	const $revenueTextLabel = document.querySelector('#revenueForm .drag-drop__selected .filename')
	
	const $expensesForm = $('#expensesForm')
	const $expensesFile = document.querySelector('#expensesFile')
	const $expensesSelected = document.querySelector('#expensesForm .drag-drop__selected')
	const $expensesTextLabel = document.querySelector('#expensesForm .drag-drop__selected .filename')
	
	if ($('.autoComplete').length) {
		fetch('https://webapp.test/api/sources').then(
			async (source) => {
				let data;
				try {
					data = Object.values(await source.json());
				}catch (e) {
					data = {}
				}
				const autoCompleteJS = new autoComplete({
					data: {
						src: data,
						cache: true,
					},
					placeHolder: "Source",
					resultsList: {
						element: (list, data) => {
							const info = document.createElement("p");
							if (data.results.length > 0) {
								info.innerHTML = `Displaying <strong>${data.results.length}</strong> out of <strong>${data.matches.length}</strong> results`;
							} else {
								info.innerHTML = `Создать категорию <strong>"${data.query}"</strong>`;
							}
							list.prepend(info);
						},
						noResults: true,
						maxResults: 15,
						tabSelect: true
					},
					resultItem: {
						element: (item, data) => {
							// Modify Results Item Style
							item.style = "display: flex; justify-content: space-between;";
							// Modify Results Item Content
							item.innerHTML = `
      <span style="text-overflow: ellipsis; white-space: nowrap; overflow: hidden;">
        ${data.match}
      </span>`;
						},
						highlight: true
					},
					events: {
						input: {
							focus: () => {
								if (autoCompleteJS.input.value.length) autoCompleteJS.start();
							}
						}
					}
				});
				autoCompleteJS.input.addEventListener("selection", function (event) {
					const feedback = event.detail;
					autoCompleteJS.input.blur();
					// Prepare User's Selected Value
					const selection = feedback.selection.value;
					// Render selected choice to selection div
					document.querySelector(".selection").innerHTML = selection;
					// Replace Input value with the selected value
					autoCompleteJS.input.value = selection;
					// Console log autoComplete data feedback
					console.log(feedback);
				});
			}
		)
	}
	
	const months = ['Jan.', 'Feb.', 'Mar.', 'Apr.', 'May', 'June', 'July', 'Aug.', 'Sept.', 'Oct.', 'Nov.', 'Dec.',]
	let $listMonths = document.querySelectorAll('#listMonths li')
	
	$listMonths.forEach((month, idx) => {
		month.textContent = months[idx]
	})
	
	
	let droppedFiles = false;
	
	function handleChangeFile (form, input, selected, textLabel) {
		form.on('drag dragstart dragend dragover dragenter dragleave drop', function(e) {
			e.preventDefault();
			e.stopPropagation();
		})
			.on('dragover dragenter', function() {
				form.addClass('is-dragover');
			})
			.on('dragleave dragend drop', function() {
				form.removeClass('is-dragover');
			})
			.on('drop', function(e) {
				console.log('drop')
				droppedFiles = e.originalEvent.dataTransfer.files;
				
				if (droppedFiles) {
					console.log('файл был выбран', droppedFiles[0].name)
					selected.classList.add('active')
					textLabel.textContent = droppedFiles[0].name
				} else {
					selected.classList.remove('active')
					console.log('Файл не был выбран')
				}
				
				console.log(droppedFiles.length)
				console.log(input.files)
				selected.classList.add('active')
			});
		

		if (input) {
			selected.addEventListener('click', function () {
				input.value = ''
				selected.classList.remove('active')
			})
			
			input.addEventListener('change', function () {
				let filename = $(this).val().replace(/.*\\/, "");
				if (this.value) {
					console.log('файл был выбран', this.value)
					console.log(this.files)
					selected.classList.add('active')
					textLabel.textContent = filename
				} else {
					selected.classList.remove('active')
					console.log('Файл не был выбран')
				}
			})
		}
			
	}

	handleChangeFile($revenueForm, $revenueFile, $revenueSelected, $revenueTextLabel)
	handleChangeFile($expensesForm, $expensesFile, $expensesSelected, $expensesTextLabel)

	
	
	

	const datepicker = document.getElementById('datepicker')
	const datepickerModal = document.getElementById('datepicker-modal')

	$('.mounthly-calc__list .title').on('click', function () {
		$(this).toggleClass('active')
		if ($(this).hasClass('active')) {
			$(this).next().toggleClass('active')
		} else {
			$(this).next().removeClass('active')
		}
	})
	
	flatpickr($('#datepicker, #datepicker-modal'), {
		mode: "range",
		minDate: "today",
		dateFormat: "d.m.Y",
		defaultDate: ["today", "today"],
		showMonths: 3,
	})
	
	flatpickr($('.monthpicker'), {
		defaultDate: new Date(),
		plugins: [
			new monthSelectPlugin({
				shorthand: true, //defaults to false
				dateFormat: "m.y", //defaults to "F Y"
				altFormat: "F Y", //defaults to "F Y"
				theme: "dark", // defaults to "light"
			})
		]
	})

	// if (datepicker.value.length === 0) {
	// 	$(`.datepicker .datepicker__icon`).css({
	// 		'left': '0'
	// 	})
	// } else if (datepicker.value.length <= 10) {
	// 	$(`.datepicker .datepicker__icon`).css({
	// 		'left': '85px'
	// 	})
	// } else {
	// 	$(`.datepicker .datepicker__icon`).css({
	// 		'left': '180px'
	// 	})
	// }

	if (datepicker) {
		datepicker.oninput = function () {
			datepicker.value = datepicker.value.replace('to', '-')
		}
	}
	
	$('#revenue').click(() => {
		$('#modal-revenue, .modal-overlay').addClass('active')
		$('html, body').addClass('_over-hidden')
	})
	$('#expenses').click(() => {
		$('#modal-expenses, .modal-overlay').addClass('active')
		$('html, body').addClass('_over-hidden')
	})
	
	
	$('.modal__close, .modal-overlay').click(() => {
		$('.modal, .modal-overlay').removeClass('active')
		$('html, body').removeClass('_over-hidden')
	})
	
	
	
	// select

	$('.select').on('click', '.select__head', function () {
		if ($(this).hasClass('open')) {
			$(this).removeClass('open');
			$(this).next().fadeOut();
		} else {
			$('.select__head').removeClass('open');
			$('.select__list').fadeOut();
			$(this).addClass('open');
			$(this).next().fadeIn();
		}
	});

	$('.select').on('click', '.select__item', function () {
		$('.select__head').removeClass('open');
		$(this).parent().fadeOut();
		$(this).parent().prev().text($(this).text());
		$(this).parent().prev().prev().val($(this)[0].dataset.prop).trigger('change');
	});

	$(document).click(function (e) {
		if (!$(e.target).closest('.select').length) {
			$('.select__head').removeClass('open');
			$('.select__list').fadeOut();
		}
	});
	
	$('.btn__edit').click(function (event) {
		event.preventDefault()
		event.stopPropagation()
		let popup_id = $('#' + $(this).attr("rel"))
		console.log(popup_id)
		$(popup_id).addClass('active')
		$('html, body').addClass('_over-hidden')
		$('.modal-overlay').addClass('active')
		$('.modal__close, .modal-overlay').click(() => {
			$('.modal, .modal-overlay').removeClass('active')
			$('html, body').removeClass('_over-hidden')
		})
	})
	
	$('#formForExpenses').on('submit', function (e) {
		e.preventDefault()
	})
	
	// if ($('#listMonths').length) {
	//
	// 	$('#listMonths li').on('click', function () {
	//
	// 		$(this).addClass('range-active')
	//		
	// 		if (!$('#listMonths li').hasClass('start')) {
	// 			// $(this).addClass('start')
	//
	// 			$(this).on('click', function () {
	// 				$(this).addClass('end')
	// 			})
	//			
	// 		}
	//		
	//
	//		
	// 		if (!$(this).hasClass('range-active')) {
	// 			$(this).addClass('range-active')
	// 		} else {
	// 			$(this).removeClass('range-active')
	// 		}
	//
	// 		if (!$('#listMonths li').hasClass('start')) {
	// 			$(this).addClass('start')
	// 		}
	//
	// 	})
	//
	// 	$('#listMonths li').on('click', function () {
	//
	// 		if ($(this).hasClass('start')) {
	//
	// 		}
	//
	// 		$(this).addClass('range-active')
	// 		$(this).addClass('start')
	// 	})
	//
	// }

})
