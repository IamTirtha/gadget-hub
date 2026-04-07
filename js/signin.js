const signCancelbutton=document.getElementById("sign-cancel-btn")
signCancelbutton.addEventListener("click" ,function(){
    window.location.href="index.html"
})

const params = new URLSearchParams(window.location.search)
const errorMsg = document.getElementById("register-error-msg")

const errorMap = {
    missing: "Please fill in all fields.",
    email: "Please enter a valid email address.",
    password: "Passwords do not match.",
    exists: "An account with this email already exists.",
    server: "Something went wrong while creating your account."
}

const error = params.get("error")

if (error && errorMsg) {
    errorMsg.textContent = errorMap[error] || "Registration failed."
    errorMsg.classList.remove("hidden")
}
