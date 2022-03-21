jQuery(function ($) {
    window.expenseInputChange = function (e) {
        console.log($(e.target).val())
        let val = parseInt($(e.target).val());
        if (val == 1) {
            console.log('1')
            $(".cat2, .cat3").hide();
            $(".cat1").show();
        }else if (val == 2) {
            console.log('2')
            $(".cat1, .cat3").hide();
            $(".cat2").show();
        }else if (val == 3) {
            console.log('3')
            $(".cat1, .cat2").hide();
            $(".cat3").show();
        }
    }

    window.cat3InputChange = function (e) {
        let val = parseInt($(e.target).val());
        if (val == 1) {
            console.log('1')
            $(".cat3_2, .cat3_3").hide();
            $(".cat3_1").show();
        }else if (val == 2) {
            console.log('2')
            $(".cat3_1, .cat3_3").hide();
            $(".cat3_2").show();
        }else if (val == 3) {
            console.log('3')
            $(".cat3_1, .cat3_2").hide();
            $(".cat3_3").show();
        }
    }

    const $autoComplete = document.querySelector('.autoComplete')

    if ($('.autoComplete').length) {
        //https://tarekraafat.github.io/autoComplete.js/demo/db/generic.json
        //https://webapp.test/sources?user_id=1
        fetch(
            backendvars.autocompleteroute
        ).then(async (source) => {
            const fetched = await source.json();
            const autoCompleteJS = new autoComplete({
                data: {
                    src: async () => {
                        let arr = [];
                        for (let key in fetched[0]) {
                            console.warn(key)
                            arr.push(fetched[0][key]);
                        }
                        console.log(arr)
                        return arr;
                    },
                    cache: true,
                },
                placeHolder: "Source",
                resultsList: {
                    element: (list, data) => {
                        const info = document.createElement("p");
                        if (data.results.length > 0) {
                            info.innerHTML = `Displaying <strong>${data.results.length}</strong> out of <strong>${data.matches.length}</strong> results`;
                        } else {
                            // info.innerHTML = `Создать категорию <strong>"${data.query}"</strong>`;
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
      </span>
      <span style="display: flex; align-items: center; font-size: 13px; font-weight: 100; text-transform: uppercase; color: rgba(0,0,0,.2);">
        ${data.value}
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
                console.log(feedback.selection.value)
                // Render selected choice to selection div
                document.querySelector(".selection").innerHTML = selection;
                // Replace Input value with the selected value
                autoCompleteJS.input.value = selection;
                // Console log autoComplete data feedback
                console.log(feedback);
            });
        });
    }
})
