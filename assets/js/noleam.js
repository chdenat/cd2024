/*******************************************************************************
 * Select => Choices
 */


document.addEventListener("DOMContentLoaded", function () {

    selectToChoices = (select)=> {

        let limit = select.dataset.limit ?? -1
        let add_search = false //Boolean(select.dataset.search ?? 'false')
        let position  = select.dataset.position ?? 'auto'

        new Choices(select, {
            itemSelectText: '',
            silent: true,
            allowHTML: true,
            shouldSort: false,
            position: position,
            searchEnabled: add_search,
            renderChoiceLimit: limit,
        })
    }
    // avoid woocommerce select transformation to choices
    document.querySelectorAll('table.variations select').forEach(woo_part => {
        woo_part.classList.add('not-auto-choices')
    })

    document.querySelectorAll('select:not(.not-auto-choices)').forEach(select => {
        selectToChoices(select)
    })

    jQuery(document).on('after.load.forminator', (event,id) => {
        selectToChoices(document.querySelector('select[id^="forminator-form-533__field--select'))
    })

})