let allProducts=[]
let filtered = [];
const filterProducts = (category) => {
    
        if (category === "all") {
        displayAllProducts(allProducts);
        return;
    }
    filtered = allProducts.filter(product => product.category === category);
    displayAllProducts(filtered);
}