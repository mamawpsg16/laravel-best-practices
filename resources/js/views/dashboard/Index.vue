<template>
  <div class="row mt-3">
    <div class="col-md-6">
      <h4 class="text-center">Tasks Monitoring</h4>
    </div>
    <div class="col-md-6">
    </div>
  </div>
  <div class="row">
    <div class="col-md-6">
      <template v-if="!isLoading && isTaskEmpty">
        <h1 class="text-center">No Tasks Found </h1>
      </template>
      <template v-else>
        <Doughnut v-if="!isLoading" :data="data" :options="options" style="width:400px; height:400px"/>
      </template>
    </div>
  </div>
</template>
<script >
import { Chart as ChartJS, ArcElement, Tooltip, Legend } from 'chart.js'
import { Doughnut } from 'vue-chartjs'
import axios from 'axios';
ChartJS.register(ArcElement, Tooltip, Legend)
  export default {
    name: 'App',

    components: {
      Doughnut,
    },

    data() {
      return {
        data: {
          labels: ['Pending', 'Ongoing', 'Completed'],
          datasets: [
            {
              backgroundColor: ['#FE0230', '#00FFFF', '#7AE81F'],
              data: []
            }
          ]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false
        },
        isLoading:false,
        defaultCount:[0, 1, 2]
      }
    },
    
    async created() {
      await this.getTaskCompletion();
    },

    computed:{
      isTaskEmpty() {
        return this.data.datasets[0].data.length === 0;
      }
    },

    methods:{
      transformData(data){
        const newData = this.defaultCount.reduce((obj, _, index) => {
          const status = index;
          obj[status] = data.find(details =>  details.status  === status) ?.count || 0;
          return obj;
        }, {});
        return Object.values(newData)
      },

      formatData(data){
        if(!data.length){
          return [];
        }
        return this.transformData(data);
      },

      updateChartData(newData) {
        this.data.datasets[0].data = newData;
      },
      async getTaskCompletion(){
        this.isLoading = true;
        await axios.get('/api/tasks-status').then(response => {
          const { data }  = response.data;
          const formattedData = this.formatData(data);
          this.updateChartData(formattedData);
          this.isLoading = false;
        })
        .catch(error => {
          console.error('Error fetching user:', error);
        });
      }
    },
    mounted(){
    },
  }
  </script>

<style scoped>
.chart-container {
  width: 400px; /* Set the desired width */
  height: 400px; /* Set the desired height */
}
</style>