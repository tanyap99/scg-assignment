<script>
import { GoogleMap, CustomMarker } from "vue3-google-map"
export default {
     components: { GoogleMap, CustomMarker },
     props: {
          method: { type: Function },
          nextPage: {
               type: String
          },
          places: {
               type: Array
          }
     },
     methods: {
          onClickLoadMore() {
               // Binding event click load more place
               return this.$emit("loadMore-location", this.nextPage)
          },
     },
}
</script>

<template>
     <div class="row">
          <div class="card text-white bg-info mt-3" v-for="place in places" v-bind:key="place.place_id">
               <div class="body-container">
                    <div class="card-header">
                         <h1 class="fw-bold"><span class="text-dark fw-bold text-uppercase">Name : </span>{{ place.name }}
                         </h1>
                    </div>
                    <div class="card-body">
                         <p class="card-text mb-0 fw-bold"><span class="text-dark fw-bold text-uppercase">Address : </span>{{
                              place.formatted_address }}</p>
                         <p class="card-text mb-0 fw-bold"><span class="text-dark fw-bold text-uppercase">Status : </span>{{
                              place?.opening_hours?.open_now ? 'Open' : 'Close' }}</p>
                         <p class="card-text mb-3 fw-bold"><span class="text-dark fw-bold text-uppercase">Rating : </span>{{
                              place?.rating }}</p>                      
                         <div>
                              <GoogleMap api-key="<API_KEY>" style="width: 100%; height: 250px"
                                   :center="place.geometry.location" :zoom="16">                              
                                   <CustomMarker
                                        :options="{ position: place.geometry.location }">
                                        <div class="text-dark fw-bolder fs-6 map-place-name">{{ place.name }}</div>
                                        <img v-bind:src="place?.icon" width="22" height="22" />
                                   </CustomMarker>
                              </GoogleMap>
                         </div>
                    </div>
               </div>
          </div>
          <button v-if="this.nextPage !== ''" type="button" class="btn btn-secondary mt-3" @click="onClickLoadMore">Load More
               Place</button>
     </div>
</template>

<style scoped>
.card {
     background: #fff;
     border-radius: 10px;
     box-shadow: 1px 1px 2rem rgba(0, 0, 0, 0.3);
     position: relative;
}

.card-header {
     background-color: transparent;
}

.body-container {
     padding: 1rem 0;
}

.map-place-name {
     margin-left: -42%;
}
</style>