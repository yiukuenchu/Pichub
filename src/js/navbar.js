
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
