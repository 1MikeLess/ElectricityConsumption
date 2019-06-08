<template>
	<div class="container-fluid px-3">
		<div class="page-header">
			<h5>Категории</h5>
		</div>

		<!-- Таблица категорий оборудования -->
		<b-table striped hover outlined dark small :fields="fields" :items="items">
			<template #btn_delete="props">
				<b-button 
					class="btn-danger"
					@click="
						delete_modal_text=props.item.name;
						delete_category_id=props.item.category_key;
						selectedCategory.name = props.item.name;
						selectedCategory.id = props.item.category_key;
						$bvModal.show('delete_msg')
					">Удалить</b-button>
			</template>
			<template #values="props">
				<div class="row justify-content-space-between">
					<transition-group class="col-8" name="fade" tag="b-form-select" v-model="selectedCategory.value" :select-size="3">
						<option v-for="val in props.item.values" :key="val" value="val" v-text="val"></option>
					</transition-group>
					<b-button
						class="btn-success col-1 col-offset-1"
						@click="
							newCategory.idValue=props.item.category_key;
							newCategory.name=props.item.name;
							$bvModal.show('add_category_value_msg')
						">+</b-button>
				<!-- 	<b-button
						class="btn-danger col-1 col-offset-1"
						@click="
							// selectedCategory.value=props.item.values;
							// selectedCategory.name=props.item.name;
							log(props);
							$bvModal.show('remove_category_msg')
						">x</b-button> -->
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
			@ok="add_category(this); newCategory.name='';"
		>
			<label for="newCategoryName">Наименование категории: </label>
			<b-form-input placeholder="Место установки" v-model="newCategory.name" id="newCategoryName"></b-form-input>
		</b-modal>
	
		<!-- Удаление значения категории -->
		<!-- <b-modal
			id="remove_category_msg"
			header-bg-variant="danger"
			header-text-variant="light"
			title="Удалить"
			ok="Подтвердить"
			cancelTitle="Отмена"
			ok-variant="danger"
			@ok="remove_category_value()"
		>
			<label for="removingCategoryName">Удалить значение категории '{{selected}}' для '{{selectedCategory.name}}'?</label>
		</b-modal> -->

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
			<label>Новое значение категории для '{{selectedCategory.name}}'</label>
			<b-form-input placeholder="Цех 10А" v-model="selectedCategory.value"></b-form-input>
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

	      	// new_category_name: null,
	      	// new_category_value: null,
	      	// new_category_id_value: null,
	      	// new_category_name_value: null,

	      	newCategory: {},
	      	selectedCategory: {},

	      	__last_cat_vals: null,

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
	      		// { name: 'Место установки', category_key: 0, values: ['Цех 1', 'Цех 2'] },
	      		// { name: 'Назначение', category_key: 1, values: ['Обработка', 'Шлифование'] }
	      	],
	      }
	    },
	    methods: {
	    	log(msg){
	    		console.log(msg)
	    	},
	    	delete_category(category_key) {
	    		this.$axios.post(`db_query_delete_category/${category_key}`).then(()=>{
	    			this.items.splice(this.items.indexOf(this.items.find(item => item.category_key === this.delete_category_id)), 1)
	    		})
	    	},
	    	add_category() {
	    		this.newCategory.id = this.max_category_key+1
	    		this.items.push({
	    			name: this.newCategory.name,
	    			category_key: this.newCategory.id,
	    			values: [],
	    		})
	    		this.$axios.post(`db_query_add_category/${this.newCategory.id}/${this.newCategory.name}`)
	    	},
	    	add_category_value() {
	    		let category = this.items.find(item => item.category_key === this.newCategory.idValue)
	    		this.$axios.post(`db_query_add_category_value/${this.newCategory.idValue}/${this.selectedCategory.value}`)
	    		.then(()=>{
	    			category.values.push(this.selectedCategory.value)
	    		})
	    	},
	    },
	    computed: {
	    	max_category_key: {
	    		get: function() {
	    			return this.items.length > 0 ? Math.max.apply(null, this.items.map(item => item.category_key)) : 0
	    		}
	    	}
	    },
	    mounted: function() {
	    	this.$axios.get('db_query_get_categories').then(query => {
	    		query.data.forEach(category => {
	    			this.$axios.get('db_query_get_category_values/'+category.category_id)
	    			.then((result) => {
		    			this.items.push({
			    			name: category.name,
			    			category_key: category.category_id,
			    			values: result.data,
		    			})
	    			})
	    		})
	    	})
	    }
  	}
</script>
