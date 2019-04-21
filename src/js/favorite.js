// Element variables
const btns = [...document.getElementsByClassName('fav-btn')];
const favText = '<span class="fas fa-heart"></span>';
const favTitle = 'Click to favorite this item';
const favedText = '<span class="fas fa-heart text-danger"></span>';
const favedTitle = 'Click to unfavorite this item';
const btnFilter = document.getElementById('btn-filter');
const filterBox = document.getElementById('filter-options');
let favorites = [];

// Toggles showing or hiding the filter box
function toggleFilterBox() {
  filterBox.classList.toggle('d-none');
}
if (btnFilter) { btnFilter.addEventListener('click', toggleFilterBox); }

// POSTs favorite change info to server
async function modifyFavorite(body) {
  const config = {
    method: 'POST',
    body: JSON.stringify(body),
    credentials: 'same-origin',
    headers: {
      'Content-Type': 'application/json; charset=utf-8',
    },
  };
  await fetch('../favorites/modify.php', config)
    .then(blob => blob.json())
    .then(data => data.success)
    .then((success) => { if (!success) throw new Error('Error modifying'); })
    .catch(() => { throw new Error('Invalid request'); });
}

// Changes the appearance of the favorite button to show whether it is favorited or not
function toggleFavBtn(favBtn, index) {
  if (index !== -1) {
    favBtn.classList.remove('btn-secondary');
    favBtn.classList.add('btn-success');
    favBtn.innerHTML = (favBtn.dataset.type === 'circle') ? favText : `${favText} Favorite`;
    favBtn.title = favTitle;
    favorites.splice(index, 1);
  } else {
    favBtn.classList.remove('btn-success');
    favBtn.classList.add('btn-secondary');
    favBtn.innerHTML = (favBtn.dataset.type === 'circle') ? favedText : `${favedText} Favorited`;
    favBtn.title = favedTitle;
    favorites.push(favBtn.dataset.item);
  }
}

// Gets the item id and determines whether to add or delete the item from favorites
function toggleFavorite(e) {
  const target = e.currentTarget;
  const index = favorites.indexOf(target.dataset.item);
  const body = { itemID: target.dataset.item };
  body.action = (index !== -1) ? 'Delete' : 'Add';
  modifyFavorite(body)
    .then(() => { toggleFavBtn(target, index); })
    .catch(() => console.error('Error favoriting'));
}

// Add event listeners and change buttons that are favorited already
function updateBtns(favs) {
  favorites = favs;
  btns.forEach((btn) => {
    btn.addEventListener('click', toggleFavorite);
    if (favorites.includes(btn.dataset.item)) {
      btn.classList.remove('btn-success');
      btn.classList.add('btn-secondary');
      btn.innerHTML = (btn.dataset.type === 'circle') ? favedText : `${favedText} Favorited`;
      btn.title = favedTitle;
    }
  });
}

// Hides all the favorite buttons if the user is not logged in
function hideBtns() {
  btns.forEach((btn) => {
    btn.classList.add('d-none');
  });
}

// Fetches the favorites from the database
function getFavorites() {
  fetch('../favorites/modify.php')
    .then(blob => blob.json())
    .then((data) => {
      if (data.success) {
        updateBtns(data.favorites);
      } else {
        hideBtns();
      }
    })
    .catch(() => console.error('Error fetching favorites'));
}
getFavorites();
