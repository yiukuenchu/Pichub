(function() {
  const BASE_PRICE = 10;
  const buttons = [...document.getElementsByClassName('border-toggle')];
  const image = document.getElementById('js-img-item');
  const lblPrice = document.getElementById('js-price');

  buttons.forEach((item) => item.addEventListener('click', (e) => {
    image.classList = e.target.dataset.class;
    lblPrice.innerHTML = '$' + (BASE_PRICE + parseInt(e.target.dataset.price));
  }));
})();