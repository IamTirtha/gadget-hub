const cancelButton = document.getElementById("cancel-btn")
cancelButton.addEventListener("click",function(){
    window.location.href="index.html"
})

const params = new URLSearchParams(window.location.search);
if (params.get("error")) {
    document.getElementById("error-msg").classList.remove("hidden");
}

if (params.get("registered")) {
    document.getElementById("success-msg").classList.remove("hidden");
}

