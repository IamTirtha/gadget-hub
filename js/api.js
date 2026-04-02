const loadProducts = () => {
    fetch("https://dummyjson.com/products")
    .then((res) => res.json())
    .then((data) => {
        allProducts = data.products; // store original data
        displayAllProducts(allProducts);
    });
};
loadProducts();

const loadModalData = async (id) => {
    const url = `https://dummyjson.com/products/${id}`;
    const res = await fetch(url);
    const details = await res.json();
    displayModal(details);
};
