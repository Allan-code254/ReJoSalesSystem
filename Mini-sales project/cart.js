document.addEventListener('DOMContentLoaded', () => {
    const cart = [];
    const cartContainer = document.createElement('div');
    cartContainer.style.position = 'fixed';
    cartContainer.style.bottom = '20px';
    cartContainer.style.right = '20px';
    cartContainer.style.background = 'white';
    cartContainer.style.border = '1px solid #ddd';
    cartContainer.style.borderRadius = '8px';
    cartContainer.style.padding = '15px';
    cartContainer.style.width = '300px';
    cartContainer.style.boxShadow = '0 5px 15px rgba(0, 0, 0, 0.2)';
    cartContainer.innerHTML = `<h3>Cart</h3><ul></ul><p>Total: Ksh<span id="total">0</span></p>`;
    document.body.appendChild(cartContainer);

    const cartList = cartContainer.querySelector('ul');
    const totalElement = cartContainer.querySelector('#total');

    function updateCart() {
        cartList.innerHTML = '';
        let total = 0;
        cart.forEach(item => {
            const li = document.createElement('li');
            li.textContent = `Ksh{item.name} xKsh{item.quantity} - Ksh{item.price * item.quantity}`;
            cartList.appendChild(li);
            total += item.price * item.quantity;
        });
        totalElement.textContent = total.toFixed(2);
    }

    document.querySelectorAll('.add-to-cart').forEach(button => {
        button.addEventListener('click', () => {
            const productCard = button.parentElement;
            const id = productCard.dataset.id;
            const name = productCard.dataset.name;
            const price = parseFloat(productCard.dataset.price);

            const existingItem = cart.find(item => item.id === id);
            if (existingItem) {
                existingItem.quantity++;
            } else {
                cart.push({ id, name, price, quantity: 1 });
            }
            updateCart();
        });
    });
});
