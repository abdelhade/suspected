<script>
document.addEventListener('keydown', function(e) {
    if (!['Enter', 'ArrowUp', 'ArrowDown'].includes(e.key)) return;
    if (!['INPUT', 'SELECT'].includes(e.target.tagName)) return;
    if (e.target.tagName === 'SELECT' && (e.key === 'ArrowUp' || e.key === 'ArrowDown')) return;

    const formElements = Array.from(document.querySelectorAll(
        '#report-form input:not([type="hidden"]):not([disabled]), #report-form select:not([disabled]), #submit-btn'
    ));
    const index = formElements.indexOf(e.target);
    if (index > -1) {
        let next = index;
        if (e.key === 'Enter' || e.key === 'ArrowDown') { e.preventDefault(); next = index + 1; }
        else if (e.key === 'ArrowUp') { e.preventDefault(); next = index - 1; }
        if (next >= 0 && next < formElements.length) {
            formElements[next].focus();
            if (formElements[next].select && !['button', 'submit'].includes(formElements[next].type)) {
                formElements[next].select();
            }
        }
    }
});
</script>
