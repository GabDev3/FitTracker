document.addEventListener("DOMContentLoaded", () => {
    let meal = {
        meal_name: "",
        products: []
    };

    let editMeal = {
        meal_id: null,
        products: []
    };

    const addProductBtn = document.querySelector(".add-product-btn");
    const saveMealBtn = document.querySelector(".save-meal-btn");
    const mealForm = document.querySelector(".add-meal-form");
    const productList = document.getElementById("product-list"); // Element to show products
    const editProductBtn = document.querySelector(".edit-meal-form .add-product-btn");
    const saveEditMealBtn = document.querySelector(".edit-meal-form .save-edit-meal-btn");
    const editProductList = document.getElementById("edit-product-list");

    function openEditMealPopup(mealId, mealName) {
        editMeal.meal_id = mealId;
        editMeal.products = []; // Clear products list
        document.getElementById("edit-meal-name").textContent = `Editing: ${mealName}`;
        document.querySelector("input[name='meal_id']").value = mealId;
        editProductList.innerHTML = ""; // Clear any previous products in the edit form

        // Load the meal name and products into the form
        document.querySelector("input[name='meal_name']").value = mealName;
        meal.products.forEach(product => {
            const productItem = document.createElement("li");
            productItem.textContent = `${product.product_name} - ${product.product_quantity}g`;
            editProductList.appendChild(productItem);
        });

        toggleEditMealForm();
    }

    window.openEditMealPopup = openEditMealPopup;

    // Add product to the edit meal form
    editProductBtn.addEventListener("click", (e) => {
        e.preventDefault();

        const productNameInput = document.querySelector(".edit-meal-form input[name='product_name']");
        const productQuantityInput = document.querySelector(".edit-meal-form input[name='product_quantity']");

        const productName = productNameInput.value.trim();
        const productQuantity = parseInt(productQuantityInput.value, 10);

        if (productName === "" || isNaN(productQuantity) || productQuantity <= 0) {
            alert("Please enter a valid product name and quantity.");
            return;
        }

        editMeal.products.push({ product_name: productName, product_quantity: productQuantity });

        const productItem = document.createElement("li");
        productItem.textContent = `${productName} - ${productQuantity}g`;
        editProductList.appendChild(productItem);

        productNameInput.value = "";
        productQuantityInput.value = "";
    });


    // Save changes for the edited meal
    saveEditMealBtn.addEventListener("click", (e) => {
        e.preventDefault();

        const mealNameInput = document.querySelector("input[name='meal_name']");
        const mealName = mealNameInput.value.trim();

        // Debugging: check current meal name and products
        console.log("Meal Name:", mealName);
        console.log("Products:", editMeal.products);

        const products = editMeal.products; // Get the list of products for the meal

        // Check if meal name or products are missing
        if (mealName === "" || products.length === 0) {
            alert("Please enter a meal name and at least one product.");
            return;
        }

        const mealId = document.querySelector("input[name='meal_id']").value;

        const mealData = {
            meal_id: mealId,
            meal_name: mealName,
            products: products.map(product => ({
                product_name: product.product_name,
                product_quantity: product.product_quantity
            }))
        };

        fetch("/edit-meal", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(mealData)
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert("Meal updated successfully!");
                    location.reload(); // Reload page to show updated meal
                } else {
                    alert("Error: " + data.message);
                }
            })
            .catch(error => console.error("Error:", error));
    });


    addProductBtn.addEventListener("click", (e) => {
        e.preventDefault();

        const productNameInput = document.querySelector("input[name='product_name']");
        const productQuantityInput = document.querySelector("input[name='product_quantity']");

        const productName = productNameInput.value.trim();
        const productQuantity = parseInt(productQuantityInput.value, 10);

        if (productName === "" || isNaN(productQuantity) || productQuantity <= 0) {
            alert("Please enter a valid product name and quantity.");
            return;
        }

        meal.products.push({ product_name: productName, product_quantity: productQuantity });

        // Display added product in UI
        const productItem = document.createElement("li");
        productItem.textContent = `${productName} - ${productQuantity}g`;
        productList.appendChild(productItem);

        // Clear input fields
        productNameInput.value = "";
        productQuantityInput.value = "";
    });

    saveMealBtn.addEventListener("click", (e) => {
        e.preventDefault();

        const mealNameInput = document.querySelector("input[name='meal_name']");
        meal.meal_name = mealNameInput.value.trim();

        if (meal.meal_name === "" || meal.products.length === 0) {
            alert("Please enter a meal name and at least one product.");
            return;
        }

        fetch("/add-meal", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(meal)
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    alert("Meal added successfully!");
                    mealForm.reset();
                    meal = { meal_name: "", products: [] };
                    productList.innerHTML = "";
                } else {
                    alert("Error: " + data.message);
                }
            })
            .catch(error => {
                console.error("Fetch Error:", error);
                alert("Network error: " + error.message);
            });
    });
});




function toggleMealDetails(mealId) {
        const details = document.getElementById('meal-details-' + mealId);
        // Toggle the display of meal details (including nutritional information)
        details.style.display = details.style.display === 'block' ? 'none' : 'block';
    }

    function toggleMealsPopup() {
        const popup = document.getElementById('mealsPopup');
        popup.style.display = popup.style.display === 'block' ? 'none' : 'block';
    }

    function toggleForm() {
        const form = document.getElementById('mealForm');
        form.style.display = form.style.display === 'block' ? 'none' : 'block';
    }


function toggleEditMealForm() {
    const form = document.getElementById('editMealForm');
    form.style.display = form.style.display === 'block' ? 'none' : 'block';
    }

