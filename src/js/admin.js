// This is the Vue.js code that powers the item edit part of the admin panel
/* global Vue, axios */
const admin = new Vue({
  el: '#vue',
  data: {
    items: [],
    formData: {},
    errors: {},
    pageMessage: {},
    messageVisible: false,
  },
  methods: {
    // displays a status message after an action is completed
    showMessage(type, message) {
      this.pageMessage = { type, message };
      this.messageVisible = true;
      setTimeout(() => this.messageVisible = false, 3000);
    },
    // gets the info for all of the items to be displayed in the table
    fetchAllItems() {
      axios.get('../../item/info.php')
        .then(response => response.data)
        .then((data) => {
          if (data.successful) {
            this.items = data.items;
          } else { throw Error(); }
        })
        .catch(() => this.showMessage('text-danger', 'Error fetching items, please try again.'));
    },
    // gets the info for a particular item and opens the modal to edit it
    openEditModal(id) {
      // id is -1 if user clicks the + plus button to add a new item
      if (id === -1) {
        this.formData = { action: 'add' };
        this.$refs.editModal.show(); // shows the modal
        return;
      }
      // get info for the specific item
      axios.get(`../../item/info.php/?id=${id}`)
        .then(response => response.data)
        .then((data) => {
          if (data.successful) {
            this.formData = Object.assign({ action: 'update' }, data.itemInfo);
          } else { throw Error(); }
        })
        .then(() => this.$refs.editModal.show())
        .catch(() => this.showMessage('text-success', 'Error fetching item info'));
    },
    // submits the info from the form to be updated in the database
    submitForm() {
      axios.post('update.php', this.formData)
        .then(response => response.data)
        .then((status) => {
          if (status.successful) {
            this.showMessage('text-success', 'Item updated!');
            this.errors = {};
            this.$refs.editModal.hide();
            this.fetchAllItems();
          } else {
            this.errors = status.errors;
          }
        })
        .catch(() => {
          this.$refs.editModal.hide();
          this.showMessage('text-danger', 'Error updating item');
        });
    },
    // posts to have an item deleted from the database
    deleteItem(id) {
      this.formData = { action: 'delete', productID: id };
      axios.post('update.php', this.formData)
        .then(response => response.data)
        .then((status) => {
          if (status.successful) {
            this.showMessage('text-success', 'Item deleted!');
            this.fetchAllItems();
          } else { throw Error(); }
        })
        .catch(() => this.showMessage('text-danger', 'Error deleting item'))
        .finally(() => this.$refs.editModal.hide());
    },
    // hides the delete confirmation popup
    dismissPopover() {
      this.$root.$emit('bv::hide::popover');
    },
  },
  // calls the fetchAllItems function when the page loads
  mounted() {
    this.fetchAllItems();
  },
});
