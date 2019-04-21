// Vue.js code to show all order info in the admin panel
/* global Vue, axios */
const orders = new Vue({
  el: '#vue',
  data: {
    orderInfo: {},
    filterBox: false,
  },
  methods: {
    // fetches order info and shows the modal
    openOrderModal(id) {
      axios.get(`info.php?orderID=${id}`)
        .then(response => response.data)
        .then((data) => {
          if (data.successful) {
            this.orderInfo = data.orderInfo;
          } else { throw Error(); }
        })
        .then(() => this.$refs.orderModal.show())
        .catch(() => console.log('error'));
    },
    // submits the form when clicking the save button on the modal
    submitForm() {
      document.getElementById('form').submit();
    },
    // updates the filtering whenever an option is changed without having to manually click submit
    updateFilter() {
      document.getElementById('filterForm').submit();
    },
  },
});
