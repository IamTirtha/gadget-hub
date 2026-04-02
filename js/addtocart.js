const cartContainer = document.getElementById("cart-container");
const buttons = document.querySelectorAll(".add-to-cart");
    buttons.forEach((button) => {
        button.addEventListener("click", () => {
            const name = button.getAttribute("data-name");
            const price = button.getAttribute("data-price");
            const img = button.getAttribute("data-img");
    
            const card = document.createElement("div");
            card.classList = "flex items-center gap-4 bg-gray-800 p-2 rounded";
            card.innerHTML = `
            <img src="${img}" class="w-12 h-12 object-cover"/>
            <div>
                <h3 class="text-sm font-bold">${name}</h3>
                <p class="text-xs">$${price}</p>
            </div>
            `;
            cartContainer.appendChild(card)
            cartPanel.classList.remove("hidden");
    });
});

const cartBtn = document.getElementById("cart-btn");
const cartPanel = document.getElementById("cart-panel");

cartBtn.addEventListener("click", () => {
    cartPanel.classList.toggle("hidden");
});