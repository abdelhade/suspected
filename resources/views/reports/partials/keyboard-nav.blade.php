<script>
document.addEventListener('keydown', function(e) {
    if (!['Enter', 'ArrowUp', 'ArrowDown'].includes(e.key)) return;

    // Only apply to inputs and selects
    if (!['INPUT', 'SELECT'].includes(e.target.tagName)) return;
    
    // Let select dropdowns use arrows normally
    if (e.target.tagName === 'SELECT' && (e.key === 'ArrowUp' || e.key === 'ArrowDown')) {
        return;
    }

    const formElements = Array.from(document.querySelectorAll('#report-form input:not([type="hidden"]):not([disabled]), #report-form select:not([disabled]), #submit-btn'));
    const index = formElements.indexOf(e.target);
    
    if (index > -1) {
        let nextIndex = index;
        if (e.key === 'Enter' || e.key === 'ArrowDown') {
            e.preventDefault();
            nextIndex = index + 1;
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            nextIndex = index - 1;
        }
        
        if (nextIndex >= 0 && nextIndex < formElements.length) {
            formElements[nextIndex].focus();
            // Select text if it's a text input
            if(formElements[nextIndex].select && formElements[nextIndex].type !== 'button' && formElements[nextIndex].type !== 'submit') {
                formElements[nextIndex].select();
            }
        }
    }
});
</script>
