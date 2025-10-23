// Crear el modal dinámicamente
const modalHTML = `
  <div id="carrito-modal" class="carrito oculto">
    <h2>Carrito de compras</h2>
    <div id="items-carrito"></div>
    <p><strong>Total: $<span id="total-carrito">0</span></strong></p>
    <button onclick="window.location.href='pago.html'">Ir a pagar</button>
    <button onclick="cerrarCarrito()">Cerrar</button>
  </div>
`;
document.body.insertAdjacentHTML("beforeend", modalHTML);

// Variables
const carrito = [];
const itemsCarrito = document.getElementById("items-carrito");
const totalCarrito = document.getElementById("total-carrito");

// Botón existente en la barra (el ícono de carrito)
document.addEventListener("DOMContentLoaded", () => {
  const btnCarrito = document.getElementById("abrir-carrito");
  if (btnCarrito) {
    btnCarrito.addEventListener("click", () => {
      document.getElementById("carrito-modal").classList.toggle("oculto");
    });
  }
});

  //document.getElementById("carrito-modal").classList.toggle("oculto");

function cerrarCarrito() {
  document.getElementById("carrito-modal").classList.add("oculto");
}

// Manejo de productos
document.querySelectorAll('.contenedor-pan').forEach((pan, index) => {
  const addBtn = pan.querySelector('.cart-button');
  const nombre = pan.querySelector('h2').innerText;
  const precioUnitario = parseInt(pan.querySelector('.precio-unitario').dataset.precio);
  const cantidadElem = pan.querySelector('.cantidad');

  addBtn.addEventListener('click', () => {
    const cantidad = parseInt(cantidadElem.innerText);
    const existente = carrito.find(p => p.nombre === nombre);

    if (existente) {
      existente.cantidad += cantidad;
    } else {
      carrito.push({ nombre, precioUnitario, cantidad });
    }

    actualizarCarrito();
  });
});

function actualizarCarrito() {
  itemsCarrito.innerHTML = "";
  let total = 0;

  carrito.forEach(item => {
    const subtotal = item.precioUnitario * item.cantidad;
    total += subtotal;
    itemsCarrito.innerHTML += `
      <p>${item.nombre} × ${item.cantidad} = $${subtotal}</p>
    `;
  });

  totalCarrito.textContent = total;

  // Guardar en localStorage
  localStorage.setItem("carrito", JSON.stringify(carrito));
}
  