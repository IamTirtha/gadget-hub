const buttons = document.querySelectorAll(".filter-btn");

const setActiveButton = (clickedBtn) => {
    buttons.forEach(btn => {
        btn.classList.remove("bg-blue-600");
        btn.classList.add("bg-gray-700");
    });

    clickedBtn.classList.remove("bg-gray-700");
    clickedBtn.classList.add("bg-blue-600");
};

buttons.forEach(btn => {
    btn.addEventListener("click", () => {
        const category = btn.dataset.category; // from HTML
        setActiveButton(btn);
        filterProducts(category);
    });
});

