<script>
export default {
  props: {
    method: { type: Function },
  },
  data: () => ({
    // Set default value of textbox to Bang sue
    location: "Bang sue",
  }),
  mounted() {
    // After load page will search with default value (Bang sue)
    this.$emit("search-location", this.location)
  },
  methods: {
    onSearch() {
      // Binding event search location when user enter textbox
      return this.$emit("search-location", this.location)
    },
  },
}
</script>

<template>
  <div class="row">
    <div class="search-input">
      <img class="map-icon" src="../assets/map-pin.svg" width="42" height="48" />      
      <input id="location-input" placeholder="Press Enter to Search Location" v-model="location" type="text"
        class="form-control" @keyup.enter="onSearch" />
    </div>
  </div>
</template>


<style scoped>
.search-input img {
  position: absolute;
}

.search-input {
  width: 100%;
  margin-bottom: 10px;
  padding: 0;
}

.map-icon {
  padding: 10px;
  min-width: 42px;
  margin-left: 4px;
}

.form-control {
  padding-left: 42px;
  border-radius: 32px;
  height: 48px;
  font-size: 18px;
  font-family: 'Roboto';
}
</style>