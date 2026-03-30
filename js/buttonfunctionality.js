const buttons=document.querySelectorAll(".filter-btn")

const setActiveButton=(clickedBtn)=>{
    buttons.forEach(btn=>{
        btn.classList.remove("bg-blue-600","text-white");
        btn.classList.add("bg-gray-700","text-white");
    })
    clickedBtn.classList.remove("bg-gray-700" ,"text-white")
    clickedBtn.classList.add("bg-blue-600" ,"text-white")
}

document.getElementById("allbtn").addEventListener("click", (e) => {
    setActiveButton(e.target);
    displayAllProducts(allProducts);
});

document.getElementById("beautybtn").addEventListener("click", (e) => {
    setActiveButton(e.target);
    displayAllProducts(filtered)
});

document.getElementById("fragrancesbtn").addEventListener("click", (e) => {
    setActiveButton(e.target);
    displayAllProducts(filtered)
});

document.getElementById("furniturebtn").addEventListener("click", (e) => {
    setActiveButton(e.target);
    displayAllProducts(filtered)
});

document.getElementById("groceriesbtn").addEventListener("click", (e) => {
    setActiveButton(e.target);
    displayAllProducts(filtered)
});