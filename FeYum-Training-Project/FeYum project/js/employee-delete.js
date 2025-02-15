document.addEventListener('DOMContentLoaded', function() {
    // Get necessary values from meta tags
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const employeeId = document.querySelector('meta[name="employee-id"]').getAttribute('content');
    
    // Add event listener to delete button
    document.querySelector('.delete-btn').addEventListener('click', function(e) {
        e.preventDefault();
        
        // Confirmation dialog
        if (confirm('هل أنت متأكد من رغبتك في حذف هذا الموظف؟ هذا الإجراء لا يمكن التراجع عنه.')) {
            // Create form element
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '';
            
            // Add CSRF token input
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = 'csrf_token';
            csrfInput.value = csrfToken;
            
            // Add delete employee input
            const deleteInput = document.createElement('input');
            deleteInput.type = 'hidden';
            deleteInput.name = 'delete_employee';
            deleteInput.value = '1';
            
            // Append inputs to form
            form.appendChild(csrfInput);
            form.appendChild(deleteInput);
            
            // Add form to body and submit
            document.body.appendChild(form);
            form.submit();
        }
    });
});