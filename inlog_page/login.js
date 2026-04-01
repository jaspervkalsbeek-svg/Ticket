function showform(formId) 
{
    document.querySelectorAll(".form-box").forEach(form => form.classList.remove("active"));
    document.getElementById(formId).classList.add("active");
    
}

document.getElementById("show-login-form").addEventListener("click", function() {
    showform("login-form");
});

document.getElementById("show-register-form").addEventListener("click", function() {
    showform("register-form");
});
