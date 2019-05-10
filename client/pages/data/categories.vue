<template>
	<div class="container-fluid px-3">
		<div class="page-header">
			<h5>Категории</h5>
		</div>

		<!-- Таблица категорий оборудования -->
		<b-table striped hover outlined dark small :fields="fields" :items="items">
			<template #btn_delete="props">
				<span v-html="props.field.html" @click="delete_modal_text=props.item.name; delete_category_id=props.item.category_key; $bvModal.show('delete_msg')"></span>
			</template>
			<template #values="props">
				<transition-group name="fade" tag="b-form-select" v-model="selected" :select-size="3">
					<option v-for="val in props.item.values" :key="val" value="val" v-html="val"></option>
					<option 
						key="new_category_value" 
						value="add" 
						class="text-secondary font-italic"
						@click="add_category_value"
					>*Новое значение</option>
				</transition-group>

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
	      	fields: {
	      		name: {
	      			label: 'Наименование',
	      			sortable: true
	      		}, 
	      		values: {
	      			label: 'Значение'
	      		},
	      		btn_delete: {
	      			label: 'Удаление',
	      			html: '<div class="btn btn-danger">Удалить</div>'
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
	    		this.items.push({name: this.new_category_name, category_key: 2})
	    		console.log(this.new_category_name)
	    	},
	    	add_category_value(category_key) {

	    	}
	    }
  	}
</script>

<style lang="sass" scoped>
	// .fade-enter-active, .fade-leave-active 
	//   transition: opacity .5s
	
	// .fade-enter, .fade-leave-to /* .fade-leave-active до версии 2.1.8 */ 
	//   opacity: 0
	
</style>