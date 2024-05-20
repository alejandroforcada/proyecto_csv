
$(document).ready(function(){ 
    let checkboxes = document.getElementsByClassName('checkbox');
for (var i = 0; i < checkboxes.length; i++) {
    checkboxes[i].addEventListener('change', function() {
        this.closest('form').submit();
    });
}
});
    
