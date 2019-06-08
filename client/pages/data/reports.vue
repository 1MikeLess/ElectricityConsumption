<template lang="html">
  <div class="component-wrapper">
    <GChart
       type="LineChart"
       :data="chartData"
       :options="chartOptions"
      />
    <div class="info">
    	<h5>Учтено {{num_of_periods}} периодов с {{start_period}} по {{end_period}}</h5>
    </div>
  </div>
</template>
<script>
import { GChart } from 'vue-google-charts'
export default {
	mounted() {
      this.$axios.get('get_electricity_consumption_info').then(callback=>{
      	this.chartData.unshift(callback.data.settings)
      	let dates = this.unique(callback.data.values.map(v=>v.date))
      	dates.forEach(date => {
      		let _row = callback.data.values.filter(v=>v.date===date).map(v=>v.power)
      		_row.unshift(date)
      		this.chartData.push(_row)
      	})
      	this.num_of_periods = dates.length
      	this.start_period = dates[0]
      	this.end_period = dates[dates.length-1]
      })
    },
  data () {
    return {
    	num_of_periods: 0,
    	start_period: null,
    	end_period: null,

      	chartData: [],
	    chartOptions: {
	        chart: {
	          	title: 'Расход энергоресурсов',
	          	subtitle: 'Потребление электроэнергии за последние месяцы',
	        }
	      }
	  }
	},
  components: {
    GChart
  },
  methods: {
  	unique(arr){
	  var obj = {}

	  for (var i = 0; i < arr.length; i++) {
	    var str = arr[i];
	    obj[str] = true; // запомнить строку в виде свойства объекта
	  }
	  return Object.keys(obj) // или собрать ключи перебором для IE8-
	}
  }

}
</script>