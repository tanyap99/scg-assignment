<script setup>
import SearchLocation from "./components/SearchLocation.vue"
import PlaceResults from "./components/PlaceResults.vue"
</script>

<template>
  <div class="container-fluid">
    <SearchLocation @search-location="onSearchLocation" />
    <PlaceResults @loadMore-location="onLoadMoreLocation" v-bind:nextPage="nextPageToken" v-bind:places="placeResults" />
    <p class="text-center" v-if="isLoading">Loading...</p>
  </div>
</template>

<script>
export default {
  data() {
    return {
      serviceUrl: "http://127.0.0.1:8000/api/searchRestaurant/",
      placeResults: [],
      locationSearch: "",
      nextPageToken: "",
      isLoading: false
    }
  },
  methods: {
    onSearchLocation(locationValue) {
      // Get value location from textbox search location
      this.locationSearch = locationValue      
      if (locationValue !== "") {
        // Show loading... text
        this.isLoading = true
        // Get place data from search restaurant API by passing parameter location
        fetch(this.serviceUrl + locationValue)
          .then(response => response.json())
          .then(data => {            
            if (data?.statusCode === 200) {
              // Set value place data from API
              this.placeResults = data?.data?.results
              // Set value page token from API
              this.nextPageToken = data?.data?.next_page_token ?? ""
              // Hide loading... text
              this.isLoading = false
            }

          })
      }
    },
    onLoadMoreLocation(pageId) {
      // Get value next page token for load more place data
      if (pageId !== "") {
        // Show loading... text
        this.isLoading = true
        // Get place data from search restaurant API by passing parameter location and pageid
        fetch(this.serviceUrl + this.locationSearch + '/' + this.nextPageToken)
          .then(response => response.json())
          .then(data => {            
            if (data?.statusCode === 200) {
              // Add more place data from API by merging new data with previous data
              this.placeResults = [...this.placeResults, ...data?.data?.results]
              // Set value page token from API
              this.nextPageToken = data?.data?.next_page_token ?? ""
              // Hide loading... text
              this.isLoading = false
            }
          })
      }
    }
  },
}
</script>
