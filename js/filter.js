let allProducts=[]
const filterProducts = (category) => {
    
        if (category === "all") {
        displayAllProducts(allProducts);
        return;
    }

        const filtered = allProducts.filter(product => 
        product.category === category
    );
    displayAllProducts(filtered);
}