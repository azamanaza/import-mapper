const loaderState = {
  state: {
    loading: true
  },
  show() {
    this.state.loading = true;
  },
  hide() {
    this.state.loading = false;
  }
};

export default loaderState;
