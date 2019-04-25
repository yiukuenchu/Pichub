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
    showMessage(type, message) {
      this.pageMessage = { type, message };
      this.messageVisible = true;
      setTimeout(() => this.messageVisible = false, 3000);
    },
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
    openEditModal(id) {
      if (id === -1) {
        this.formData = { action: 'add' };
        this.$refs.editModal.show(); 
        return;
      }
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
    dismissPopover() {
      this.$root.$emit('bv::hide::popover');
    },
  },
  mounted() {
    this.fetchAllItems();
  },
});
