const signUpButton =document.getElementById("signup-btn")
signUpButton.addEventListener("click" , function(){
    localStorage.setItem("isSignedIn","true")
    window.location.href="loginform.html"
})

const signCancelbutton=document.getElementById("sign-cancel-btn")
signCancelbutton.addEventListener("click" ,function(){
    localStorage.setItem("isSignedIn","false")
    window.location.href="index.html"
})