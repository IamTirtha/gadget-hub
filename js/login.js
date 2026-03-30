const loginButton = document.getElementById("login-btn")
loginButton.addEventListener("click" , function(){
const loginInfo = document.getElementById("input-email").value
const passInfo = document.getElementById("input-password").value
const errormsg =document.getElementById("error-msg")

// dummyEmail and Password
const correctEmail="admin"
const correctPassword="admin123"

if(loginInfo===correctEmail && passInfo===correctPassword){
    localStorage.setItem("isLoggedIn","true")
    window.location.href="dashboard.html"
}
else{
    errormsg.classList.remove("hidden")
}
})



const cancelButton = document.getElementById("cancel-btn")
cancelButton.addEventListener("click",function(){
    window.location.href="index.html"
})

