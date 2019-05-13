<template>
	<div class="container-fluid px-3">
		<div class="page-header">
			<h5>Категории</h5>
			<h1>{{max_category_key}}</h1>
		</div>

		<!-- Таблица категорий оборудования -->
		<b-table striped hover outlined dark small :fields="fields" :items="items">
			<template #btn_delete="props">
				<b-button 
					class="btn-danger"
					@click="
						delete_modal_text=props.item.name;
						delete_category_id=props.item.category_key;
						$bvModal.show('delete_msg')
					">Удалить</b-button>
			</template>
			<template #values="props">
				<div class="row justify-content-around">
					<transition-group class="col-10" name="fade" tag="b-form-select" v-model="selected" :select-size="3">
						<option v-for="val in props.item.values" :key="val" value="val" v-text="val"></option>
					</transition-group>
					<b-button
						class="btn-success col-1"
						@click="
							new_category_id_value=props.item.category_key;
							new_category_name=props.item.name;
							$bvModal.show('add_category_value_msg')
						">+</b-button>
				</div>
				
			</template>
		</b-table>
		<b-button @click="$bvModal.show('add_category_msg')">Добавить категорию</b-button>

		<!-- Удаление категорий -->
		<b-modal 
			id="delete_msg"
			header-bg-variant="danger"
			header-text-variant="light"
			title="Удаление"
			okTitle="Подтвердить"
			cancelTitle="Отмена"
			ok-variant="danger"
			@ok="delete_category(delete_category_id)"
		>Выполнить удаление '{{delete_modal_text}}'?</b-modal>

		<!-- Добавление новых категорий -->
		<b-modal
			id="add_category_msg"
			header-bg-variant="success"
			header-text-variant="light"
			title="Добавить новую категорию"
			ok="Подтвердить"
			cancelTitle="Отмена"
			ok-variant="success"
			@ok="add_category(this)"
		>
			<span>Наименование категории: </span>
			<b-form-input placeholder="Место установки" v-model="new_category_name"></b-form-input>
		</b-modal>

		<!-- Добавление нового значения категории -->
		<b-modal
			id="add_category_value_msg"
			header-bg-variant="success"
			header-text-variant="light"
			title="Добавить новое значение категории"
			ok="Подтвердить"
			cancelTitle="Отмена"
			ok-variant="success"
			@ok="add_category_value"
		>
			<span>Новое значение категории для '{{new_category_name}}'</span>
			<b-form-input placeholder="Цех 10А" v-model="new_category_value"></b-form-input>
		</b-modal>
	</div>
</template>

<script>
	export default {
		name: 'categories',
	    data() {
	      return {
	      	selected: null,

	      	delete_modal_text: '???',
	      	delete_category_id: null,

	      	new_category_name: null,
	      	new_category_value: null,
	      	new_category_id_value: null,
	      	new_category_name_value: null,

	      	fields: {
	      		category_key: {
	      			label: 'ID',
	      			sortable: true
	      		},
	      		name: {
	      			label: 'Наименование',
	      			sortable: true
	      		}, 
	      		values: {
	      			label: 'Значение'
	      		},
	      		btn_delete: {
	      			label: 'Удаление'
	      		}
	      	},
	      	items: [
	      		{ name: 'Место установки', category_key: 0, values: ['Цех 1', 'Цех 2'] },
	      		{ name: 'Назначение', category_key: 1, values: ['Обработка', 'Шлифование'] }
	      	],
	      }
	    },
	    methods: {
	    	delete_category(category_key) {
	    		this.items.splice(category_key, 1)
	    		console.log(`Удалить категорию ${category_key}!`)
	    	},
	    	add_category() {
	    		this.items.push({
	    			name: this.new_category_name,
	    			category_key: this.max_category_key+1,
	    			values: []
	    		})
	    		console.log(this.new_category_name)
	    	},
	    	add_category_value() {
	    		console.log(this.items.find(item => item.category_key === this.new_category_id_value).category_key)
	    		this.items.find(item => item.category_key === this.new_category_id_value)
	    			.values.push(this.new_category_value)
	    	},
	  //   	getMaxOfArray(numArray) {
			// 	return Math.max.apply(null, numArray)
			// }
	    },
	    computed: {
	    	max_category_key: {
	    		get: function() {
	    			return this.items.length > 0 ? Math.max.apply(null, this.items.map(item => item.category_key)) : 0
	    		}
	    	}
	    },
	    
  	}
</script>

<style lang="sass" scoped>
	// .fade-enter-active, .fade-leave-active 
	//   transition: opacity .5s
	
	// .fade-enter, .fade-leave-to /* .fade-leave-active до версии 2.1.8 */ 
	//   opacity: 0
	
</style>