<template>
	<div class="container-fluid">
		<div class="container">
		  	<nav class="navbar navbar-expand-md navbar-light bg-info mb-3">
			  <!-- <span class="navbar-brand text-light">Потребление электроэнергии</span> -->
			  <router-link class="navbar-brand text-light" to="/">Потребление электроэнергии</router-link>
			  <div class="collapse navbar-collapse" id="navbarNavDropdown">
			    <ul class="navbar-nav">
			    	<li class="nav-item">
				        <router-link class="nav-link text-light" to="/data/categories">Категории</router-link>
				    </li>
				    <li class="nav-item">
				        <router-link class="nav-link text-light" to="/data/equipment">Оборудование</router-link>
				    </li>
				    <li class="nav-item">
				        <router-link class="nav-link text-light" to="/data/reports">Отчеты</router-link>
				    </li>
				    <li class="nav-item">
				        <router-link class="nav-link text-light" to="/data/users">Пользователи</router-link>
				    </li>
			    </ul>
			  </div>
			</nav>

			<router-view/>
		</div>
	</div>
</template>

<script>
	import axios from 'axios'

	export default {
		name: 'cons-data',
		data() {
			return {
				// categories: null
			}
		},
		mounted() {
			// axios.get('get_hello').then(result => console.log(result.data)) // HELLO!
		},
	}
</script>

<style lang="sass" scoped>
	.bg-info
		background-color: #16b6ce !important
	.navbar
		border-bottom:
			left-radius: 12px
			right-radius: 12px
		.navbar-brand
			user-select: none
	// .container
	// 	background-image: url()
</style>