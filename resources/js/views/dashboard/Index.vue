<template>
  <div class="row mt-3">
    <div class="col-md-6">
      <h4 class="text-center">Tasks Completion</h4>
    </div>
    <div class="col-md-6">
    </div>
  </div>
  <div class="row">
    <div class="col-md-6">
      <Doughnut v-if="!isLoading" :data="data" :options="options" style="width:400px; height:400px"/>
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
              backgroundColor: ['#FE0230', '#D9D0D1', '#7AE81F'],
              data: []
            }
          ]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false
        },
        isLoading:false,
        columns: [
        {
          label: 'Action',
          field: 'action'
        },
        {
          label: 'Name',
          field: 'name',
        },
        {
          label: 'Age',
          field: 'age',
          type: 'number',
        },
        {
          label: 'Created On',
          field: 'createdAt',
        },
        {
          label: 'Percent',
          field: 'score',
          type: 'decimal' 
        },
      ],
      rows: [
        { id:1, name:"John", age: 20, createdAt: '',score: 0.03343 },
        { id:2, name:"Jane", age: 24, createdAt: '2011-10-31', score: 0.03343 },
        { id:3, name:"Susan", age: 16, createdAt: '2011-10-30', score: 0.03343 },
        { id:4, name:"Chris", age: 55, createdAt: '2011-10-11', score: 0.03343 },
        { id:5, name:"Dan", age: 40, createdAt: '2011-10-21', score: 0.03343 },
        { id:6, name:"John", age: 20, createdAt: '2011-10-31', score: 5 },
      ],
      }
    },
    async created() {
      await this.getTaskCompletion();
    },
    methods:{
      onSelectedRowsChange({ selectedRows }){
        console.log('MAIN PAGE Selected Rows:', selectedRows);
      },
      onRowClicked(row) {
          console.log('MAIN PAGE  Row clicked:', row);
          // params.row - row object 
          // params.pageIndex - index of this row on the current page.
          // params.selected - if selection is enabled this argument 
          // indicates selected or not
          // params.event - click event
      },
      formatData(data){
        if(!data.length){
          return [];
        }
        const newData = data.map(task => task.count);
        return newData;
      },
      updateChartData(newData) {
        this.data.datasets[0].data = newData;
      },
      async getTaskCompletion(){
        this.isLoading = true;
        await axios.get('/api/tasks-status').then(response => {
          const { data }  = response.data;
          const formattedData = this.formatData(data);
          console.log(formattedData,'formattedData');
          this.updateChartData(formattedData);
          this.isLoading = false;
        })
        .catch(error => {
          console.error('Error fetching user:', error);
        });
      }
    },
    mounted(){
      console.log(this.$refs['vueGoodTable'],'this.$refs.vgt');
    },
  }
  </script>

<style scoped>
.chart-container {
  width: 400px; /* Set the desired width */
  height: 400px; /* Set the desired height */
}
</style>