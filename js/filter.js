var allProducts = [];
var filteredProducts = [];

function filterProducts(category) {
    const selectedCategory = category || "all";

    if (selectedCategory === "all") {
        displayAllProducts(allProducts);
        return;
    }

    filteredProducts = allProducts.filter(product => product.category === selectedCategory);
    displayAllProducts(filteredProducts);
}
