let cart = JSON.parse(localStorage.getItem('cart')) || [];

document.querySelectorAll('.add-to-cart').forEach((button) => {
    button.addEventListener('click', (e) => {
        const item = button.closest('.menu-item');
        const name = item.querySelector('h3').textContent;
        const price = parseFloat(item.querySelector('.price').textContent.replace('₱', ''));
        const quantity = parseInt(item.querySelector('input[type="number"]').value);

        const existingItem = cart.find(cartItem => cartItem.name === name);
        if (existingItem) {
            existingItem.quantity += quantity;
        } else {
            cart.push({ name, price, quantity });
        }

        localStorage.setItem('cart', JSON.stringify(cart));

        alert(`${quantity}x ${name} added to cart!`);
        renderCart();
    });
});

function renderCart() {
    const cartContainer = document.querySelector('#cart-container');
    cartContainer.innerHTML = '';

    if (cart.length === 0) {
        cartContainer.innerHTML = '<p>Your cart is empty.</p>';
        return;
    }

    cart.forEach((item, index) => {
        const total = item.price * item.quantity;
        cartContainer.innerHTML += `
            <div class="cart-item">
                <p>${item.name}</p>
                <p>Price: ₱${item.price.toFixed(2)}</p>
                <p>Quantity: ${item.quantity}</p>
                <p>Total: ₱${total.toFixed(2)}</p>
                <button class="remove-item" data-index="${index}">Remove</button>
            </div>
        `;
    });

    document.querySelectorAll('.remove-item').forEach((button) => {
        button.addEventListener('click', (e) => {
            const index = button.getAttribute('data-index');
            cart.splice(index, 1); // Remove item from cart array
            localStorage.setItem('cart', JSON.stringify(cart)); // Update localStorage
            renderCart(); // Re-render cart
        });
    });
}

renderCart();
