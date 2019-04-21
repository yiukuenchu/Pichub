const BASE_PRICE = 10;
const buttons = [...document.getElementsByClassName('border-toggle')];
const image = document.getElementById('js-img-item');
const lblPrice = document.getElementById('js-price');
const cartFormFrame = document.getElementById('form-frame-type');

buttons.forEach(item => item.addEventListener('click', (e) => {
  const price = BASE_PRICE + parseInt(e.target.dataset.price, 10);
  image.classList = e.target.dataset.class;
  lblPrice.innerHTML = `$${price}`;
  cartFormFrame.value = e.target.innerHTML;
}));
