<template>
	<div class="container-fluid px-3">
		<div class="page-header">
			<h5>Оборудование {{max_equipment_id}}</h5>
		</div>
		<b-table striped hover outlined dark small :fields="fields" :items="items">
			<template #btn_delete="props">
				<b-button class="btn-danger" @click="$bvModal.show('delete_equipment_modal')">Удалить</b-button>
			</template>
		</b-table>

		<b-button class="btn-secondary" @click="$bvModal.show('add_equipment_modal')">Добавить оборудование</b-button>

		<b-modal
			id="add_equipment_modal"
			header-bg-variant="success"
			header-text-variant="light"
			title="Добавить новое оборудование"
			ok="Подтвердить"
			cancelTitle="Отмена"
			ok-variant="success"
			@ok="add_equipment"
		>
			<b-form @submit.prevent="onSubmit">
				<b-form-group>
					<b-input-group 
						v-for="field in form" 
						class="mb-1" 
						:prepend="field.label" 
						:key="field.key">
						<b-form-input v-model="field.model"></b-form-input>

						<b-input-group-append v-show="field.key.indexOf('categories') !== -1">
							<b-input-group-text class="text-success">[Категория]</b-input-group-text>
						</b-input-group-append>
					</b-input-group>
				</b-form-group>

				<b-button type="submit" variant="success">Submit</b-button>
      			<b-button type="reset" variant="secondary">Reset</b-button>
			</b-form>
			<!-- <b-form-input placeholder="Место установки" v-model="new_category_name"></b-form-input> -->
		</b-modal>

		<b-modal 
			id="delete_equipment_modal"
			header-bg-variant="danger"
			header-text-variant="light"
			title="Удаление"
			okTitle="Подтвердить"
			cancelTitle="Отмена"
			ok-variant="danger"
			@ok="remove_equipment"
		>Выполнить удаление оборудования'{{}}'?</b-modal>
	</div>
</template>

<script>
	export default {
		name: 'equipment',

		data() {
			return {
				new_category_name: null,

				fields: [
		      		{
		      			key: 'equipment_id',
		      			label: 'ID',
		      			sortable: true,

			      		model: 3
		      		}, {
		      			key: 'name',
		      			label: 'Наименование',
		      			sortable: true,

			      		model: ''
		      		}, {
		      			key: 'btn_delete',
		      			label: 'Удаление'
		      		}, {
		      			key: 'comment',
		      			label: 'Комментарий',
		      			sortable: true,

			      		model: ''
		      		},
	      		],
	      		items: [
	      			{ equipment_id: 1, name: 'Станок 1', comment: 'noinfo', categories: {
	      				placement: 'Цех 1', func: 'Шлифовальный станок'
	      			} },
	      			{ equipment_id: 2, name: 'Станок 2', comment: 'noinfo', categories: {
	      				placement: 'Цех 2', func: 'Обрабатывающий станок'
	      			} },
	      		],

	      		/*
					Массив полей в форме добавления нового оборудования.
					Используется для формирования полей в модальном окне,
					а также для добавления значений оборудования в БД
	      		*/
		        form: []
			}
		},
		methods: {
			add_equipment() {
				console.log(this.form)
				console.log('An equipment has been added!')
			},
			remove_equipment() {
				console.log('An equipment has been removed!')
			},
			onSubmit(evt) {
		        // evt.preventDefault()

		        // Запрос для добавления в БД нового оборудования
		        // Array.prototype.push.apply(this.items, this.form)
		        // console.log(this.items)
		        // console.log(JSON.stringify(this.form.map(f => f.model)))

		        // НЕОБХОДИМО ПРЕОБРАЗОВАТЬ MODELs ИЗ FORM в ITEM-ELEMENT
		        // this.items.push(Object.assign({}, this.form.map(item => {
		        // 	return {
		        // 		[item.key]: item.model,
		        // 	}
		        // })))

		        console.log(this.items)
		        console.log(this.form)

		    },
		    _addRow(){
		    	
		    }
		},
		computed: {
			// Максимальный индекс в таблице 'Оборудование'
			max_equipment_id() {
				return this.items.length > 0 ? Math.max.apply(null, this.items.map(item => item.equipment_id)) : 0
			}
		},
		mounted: function() {
			// Добавление категорий оборудования (загрузкой из БД)
	    	this.fields.push(
	    		{
	    			key: 'categories.placement',
			      	label: 'Размещение',
			      	sortable: true,

			      	model: ''
			    }, {
			    	key: 'categories.func',
			      	label: 'Назначение',
			      	sortable: true,

			      	model: ''
			    }
	    	)

	    	// Формирование массива для ввода в модальном окне.
	    	// Слияние массивов form и отфильтрованного fields.
	    	Array.prototype.push.apply(this.form, this.fields.filter(f => f.model !== undefined))
	    }
	}
</script>

<style scoped>
	
</style>