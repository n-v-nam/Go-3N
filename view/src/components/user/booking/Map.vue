<template>
  <div id="booking-map" class="my-10">
    <vs-col vs-w="6">
      <div class="w-full px-10">
        <p class="font-bold text-2xl font-oswald">Nhập vào thông tin để chúng tôi tìm tài xế phù hợp cho bạn:</p>
        <p class="font-light mb-6 text-xs">Nhập xong vị trí, bạn có thể di chuyển icon trên bản đồ đề xác định vị trí chính xác</p>
        <div id="location" class="mb-4"></div>
        <span id="icon-arrow" class="material-icons"> arrow_forward </span>
        <div class="mb-4">
          <vs-select placeholder="VD: Hàng ướt" label="Loại hàng hoá" v-model="itemType" class="mb-4 pr-2 w-full">
            <vs-select-item :key="index" :value="item.item_type_id" :text="item.name" v-for="(item, index) in itemTypes" />
          </vs-select>
          <vs-select placeholder="VD: Xe 10 tấn" label="Loại xe muốn thuê" v-model="categoryTruck" class="mb-4 pr-2 w-full">
            <vs-select-item :key="index" :value="item.category_truck_id" :text="item.name" v-for="(item, index) in categoryTrucks" />
          </vs-select>
        </div>
        <div class="mb-4 flex w-full flex-wrap gap-2 justify-between pr-2">
          <vs-input class="w-2/5" v-model="weightItem" placeholder="VD: 10" label="Cân nặng hàng hoá (đơn vị: kg)" />
          <vs-input class="w-2/5" v-model="heightItem" placeholder="VD: 2" label="Chiều cao hàng hoá (đơn vị: m)" />
          <vs-input class="w-2/5" v-model="lengthItem" placeholder="VD: 0.5" label="Chiều dài hàng hoá (đơn vị: m)" />
          <vs-input class="w-2/5" v-model="widthItem" placeholder="VD: 1" label="Chiều rộng hàng hoá (đơn vị: m)" />
          <vs-input class="w-2/5" v-model="count" placeholder="VD: 2" label="Số lượng thùng hàng" />
          <vs-input class="w-2/5" v-model="price" placeholder="VD: 120000" label="Giá mong muốn (đơn vị: VNĐ)" />
        </div>
        <div class="action-search">
          <vs-button color="danger" class="w-full mt-4 font-bold" icon-after icon="search" @click="onSearchPost">Tìm kiếm</vs-button>
        </div>
      </div>
    </vs-col>
    <vs-col vs-w="6">
      <div id="map"></div>
    </vs-col>
  </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'
import axios from 'axios'
import mapboxgl from 'mapbox-gl'
import config from '@/app.config.js'
import MapboxGeocoder from '@mapbox/mapbox-gl-geocoder'
import '@mapbox/mapbox-gl-geocoder/dist/mapbox-gl-geocoder.css'
export default {
  name: 'booking-map',
  data() {
    return {
      loading: false,
      location: '',
      accessToken: config.VUE_APP_MAP_ACCESS_TOKEN,
      center: [106, 21],
      locationFrom: null,
      locationTo: null,
      fromCity: null,
      toCity: null,
      itemType: null,
      count: null,
      price: null,
      categoryTruck: null,
      weightItem: null,
      heightItem: null,
      lengthItem: null,
      widthItem: null,
      map: {}
    }
  },
  watch: {
    async locationFrom(val) {
      if (val) {
        if (this.locationTo && this.locationTo.length) await this.getRouteMap()
      }
    },
    async locationTo(val) {
      if (val) {
        if (this.locationFrom && this.locationFrom.length) await this.getRouteMap()
      }
    }
  },
  computed: {
    ...mapGetters({
      categoryTrucks: 'categoryTruck/getCategoryTrucks',
      itemTypes: 'item/itemTypes'
    })
  },
  methods: {
    ...mapActions({
      fetchCategoryTrucks: 'categoryTruck/fetchCategoryTrucks',
      fetchItemTypes: 'item/fetchItemTypes',
      searchPost: 'post/searchPost'
    }),
    async createMap() {
      try {
        mapboxgl.accessToken = this.accessToken
        this.map = new mapboxgl.Map({
          container: 'map',
          style: 'mapbox://styles/mapbox/streets-v11',
          center: this.center,
          zoom: 7
        })
        let locationFrom = new MapboxGeocoder({
          accessToken: this.accessToken,
          mapboxgl: mapboxgl,
          placeholder: 'Chọn điểm bắt đầu',
          marker: false
        })

        let locationTo = new MapboxGeocoder({
          accessToken: this.accessToken,
          mapboxgl: mapboxgl,
          placeholder: 'Chọn điểm kết thúc',
          marker: false
        })

        this.map.addControl(locationFrom)
        this.map.addControl(locationTo)

        locationFrom.on('result', (e) => {
          const marker = new mapboxgl.Marker({
            draggable: true,
            color: '#D80739'
          })
            .setLngLat(e.result.center)
            .addTo(this.map)
          this.locationFrom = e.result.center
          this.fromCity = e.result.matching_text || e.result.text
          marker.on('dragend', (e) => {
            this.locationFrom = Object.values(e.target.getLngLat())
          })
        })

        locationTo.on('result', (e) => {
          const marker = new mapboxgl.Marker({
            draggable: true,
            color: '#1741c7'
          })
            .setLngLat(e.result.center)
            .addTo(this.map)
          this.locationTo = e.result.center
          this.toCity = e.result.matching_text || e.result.text
          marker.on('dragend', (e) => {
            this.locationTo = Object.values(e.target.getLngLat())
          })
        })
      } catch (err) {
        console.log('map error', err)
      }
    },
    async getRouteMap() {
      const res = await axios.get(
        `${config.API_MAPBOX_CYCLING}${this.locationFrom[0]},${this.locationFrom[1]};${this.locationTo[0]},${this.locationTo[1]}?steps=true&geometries=geojson&access_token=${this.accessToken}`
      )
      console.log(res)
      if (res && res.status == 200) {
        const data = res.data.routes[0]
        const route = data.geometry.coordinates
        const geojson = {
          type: 'Feature',
          properties: {},
          geometry: {
            type: 'LineString',
            coordinates: route
          }
        }
        if (this.map.getSource('route')) {
          this.map.getSource('route').setData(geojson)
        } else {
          this.map.addLayer({
            id: 'route',
            type: 'line',
            source: {
              type: 'geojson',
              data: geojson
            },
            layout: {
              'line-join': 'round',
              'line-cap': 'round'
            },
            paint: {
              'line-color': '#3887be',
              'line-width': 5,
              'line-opacity': 0.75
            }
          })
        }
      }
    },
    async onSearchPost() {
      const data = {
        category_truck_id: this.categoryTruck,
        item_type_id: this.itemType,
        from_city_id: this.fromCity,
        to_city_id: this.toCity,
        weight_product: this.weightItem > 10 ? this.weightItem : 10,
        price: this.price > 100000 ? this.price : 100000,
        count: this.count,
        width: this.widthItem,
        length: this.lengthItem,
        height: this.heightItem
      }
      const res = await this.searchPost(data)
      this.$emit('resultSearch', res)
    }
  },
  mounted() {
    this.createMap()

    // Move input
    const location = document.getElementById('location')
    const iconArrow = document.getElementById('icon-arrow')
    const inputLocation = document.getElementsByClassName('mapboxgl-ctrl-top-right')[0]
    inputLocation.insertBefore(iconArrow, inputLocation.lastChild)
    location.appendChild(inputLocation)
  },
  async created() {
    await this.fetchCategoryTrucks()
    await this.fetchItemTypes()
  }
}
</script>

<style lang="scss" scoped>
@import url('https://api.tiles.mapbox.com/mapbox-gl-js/v0.53.0/mapbox-gl.css');
</style>
