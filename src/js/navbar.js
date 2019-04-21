// Vue.js code that handles the navbar expansion on mobile to avoid using jQuery and Vue.js together
/* global Vue: false */
const navbar = new Vue({
  el: '#navbar',
  data: {
    collapsed: true,
  },
  methods: {
    toggleCollapsed() {
      this.collapsed = !this.collapsed;
    },
  },
});
