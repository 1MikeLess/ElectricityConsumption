<template>
	<div class="container-fluid px-3">
		<div class="page-header">
			<h5>Оборудование</h5>
		</div>
		<b-table striped hover outlined dark small :fields="fields" :items="items">
			<template #btn_delete="props">
				<b-button 
					class="btn-danger" 
					@click="
						$bvModal.show('delete_equipment_modal'); 
						current_deleting_row.name = props.item.name;
						current_deleting_row.id = props.item.equipment_id;
					">Удалить</b-button>
			</template>
		</b-table>

		<b-button 
			class="btn-secondary" 
			@click="$bvModal.show('add_equipment_modal')">Добавить оборудование</b-button>

		<b-modal
			id="add_equipment_modal"
			header-bg-variant="success"
			header-text-variant="light"
			title="Добавить новое оборудование"
			ok="Подтвердить"
			cancelTitle="Отмена"
			ok-variant="success"
		>
			<b-form @submit.prevent="onSubmit" @reset.prevent="onReset">
				<b-form-group>
					<b-input-group 
						v-for="field in fields.filter(f=>!f.isCategory && f.model!==undefined)" 
						class="mb-1" 
						:prepend="field.label" 
						:key="field.key"
						type="text"
					>
						<b-form-input
							v-model="field.model"
							:placeholder="field.placeholder"
						></b-form-input>

						<!-- <b-input-group-append v-show="field.key.indexOf('categories') !== -1">
							<b-input-group-text class="text-success">[Категория]</b-input-group-text>
						</b-input-group-append> -->
					</b-input-group>
					
					<div
						v-for="field in fields.filter(f=>f.isCategory && f.model!==undefined)"
						class="mb-2" 
					>
						<label><span class="text-success">[Категория]</span> {{field.label}}</label>
						<b-input-group>
							<b-form-select v-model="field.model">
								<option 
									v-for="opt in cat_values.filter(f=>f.categoryName === field.label)"
									:value="opt.categoryValueId">
								{{opt.name}}</option>
							</b-form-select>
						</b-input-group>
					</div>

				</b-form-group>

				<b-button type="submit" variant="success">Подтвердить</b-button>
      			<b-button type="reset" variant="secondary">Отмена</b-button>
			</b-form>
		</b-modal>

		<b-modal 
			id="delete_equipment_modal" header-bg-variant="danger" header-text-variant="light" title="Удаление" 
			okTitle="Подтвердить" cancelTitle="Отмена" ok-variant="danger" @ok="remove_equipment"
		>Выполнить удаление оборудования '{{current_deleting_row.name}}'?</b-modal>
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

			      		model: 0,
			      		placeholder: '100'
		      		}, {
		      			key: 'name',
		      			label: 'Наименование',
		      			sortable: true,

			      		model: '',
			      		placeholder: 'Станок '+Math.floor(Math.random() * 45) + 5
		      		}, {
		      			key: 'btn_delete',
		      			label: 'Удаление'
		      		}, {
		      			key: 'comment',
		      			label: 'Комментарий',
		      			sortable: true,

			      		model: '',
			      		placeholder: 'Без комментариев'
		      		},
	      		],
	      		items: [
	      			// { equipment_id: 1, name: 'Станок 1', comment: 'noinfo', categories: {
	      			// 	placement: 'Цех 1', func: 'Шлифовальный станок'
	      			// } },
	      			// { equipment_id: 2, name: 'Станок 2', comment: 'noinfo', categories: {
	      			// 	placement: 'Цех 2', func: 'Обрабатывающий станок'
	      			// } },
	      		],

				current_deleting_row: {
					name: null,
					id: null
				},
				newEquipment: {},

		        cat_values: [],
			}
		},
		methods: {
			modal(m_name) {
				$bvModal.show(m_name)
			},
			remove_equipment() {
				this.$axios.post(`db_query_remove_equipment/${this.current_deleting_row.id}`)
				.then(()=>{
					this.items.splice(this.items.indexOf(this.items.find(item=>item.equipment_id===this.current_deleting_row.id)), 1)
				})
			},
			onSubmit() {
			    this.newEquipment = {
			    	'equipment_id': this.fields.find(f=>f.key==='equipment_id').model,
			    	'name': this.fields.find(f=>f.key==='name').model,
			    	'cat_value_ids': this.fields.filter(f=>f.isCategory).map(f=>f.model),
			    	'descr': this.fields.find(f=>f.key==='comment').model,
			    }
			    // console.log(this.newEquipment)
			    this.$axios.post(`db_query_add_equipment/${JSON.stringify(this.newEquipment)}`).then(()=>{
			    	let _row = {
			    		equipment_id: this.newEquipment.equipment_id,
			    		name: this.newEquipment.name,
			    		descr: this.newEquipment.descr
			    	}
			    	this.fields.filter(f=>f.isCategory).forEach(f=>{
			    		// console.log(this.fields, f.model)
			    		_row[f.key] = this.cat_values.find(cvs=>cvs.categoryValueId===f.model).name
			    	})
			    	this.items.push(_row)
			    })
			    .catch(()=>{
			    	alert('[ОШИБКА] Новое оборудование не было добавлено! Введите корректный ID.')
			    	this.fields.find(f=>f.key==='equipment_id').model = this.max_equipment_id+1
			    })
		    },
		    onReset(){
		    	this.fields.filter(f=>f.model!==undefined).forEach(f=>f.model = '')
		    	this.fields.find(f=>f.key='equipment_id').model = this.max_equipment_id+1
		    	// console.log('ddddddddddd')
		    },
		},
		computed: {
			// Максимальный индекс в таблице 'Оборудование'
			max_equipment_id() {
				return this.items.length > 0 ? Math.max.apply(null, this.items.map(item => item.equipment_id)) : 0
			}
		},
		mounted: function() {
			// Добавление категорий оборудования (загрузкой из БД)
	    	// this.fields.push(
	    	// 	{
	    	// 		key: 'categories.placement',
			   //    	label: 'Место установки',
			   //    	sortable: true,

			   //    	model: ''
			   //  }, {
			   //  	key: 'categories.func',
			   //    	label: 'Назначение',
			   //    	sortable: true,

			   //    	model: ''
			   //  }
	    	// )
	    	this.$axios.get('db_query_get_categories').then(callback => {
	    		callback.data.forEach(cat => {
	    			// console.log(`categories[${cat.name}]`)
	    			this.fields.splice(-2, 0, {
	    				key: `categories[${cat.name}]`,
			      		label: cat.name,
			      		sortable: true,

			      		model: '',
			      		isCategory: true,
			      		category: cat
	    			})
	    		})
	    	})
	    	this.$axios.get('db_query_get_equipment').then(callback => {
	    		callback.data.forEach(eq => {
	    			// let cats = this.fields.filter(cell => cell.key.includes('categories')).map(cell => cell.key.substr(11))
	    			this.$axios.get(`db_query_get_equipment_categories/${eq.equipment_id}`)
	    			.then(callback => {
	    				let _row = {
		    				equipment_id: eq.equipment_id,
		    				name: eq.name,
		    				comment: eq.descr,
		    			}
	    				callback.data.forEach(cat => {
	    					_row[this.fields.find(f => f.label === cat.name).key] = cat.cat_value_name
	    					// console.log(_row)
	    				})
	    				this.items.push(_row)
	    				this.fields.find(kf=>kf.key==='equipment_id').model = this.max_equipment_id+1
	    			})
	    		})
	    	})

	    	this.$axios.get('db_query_get_category_values').then(callback => {
	    		this.cat_values = callback.data
	    		// console.log(callback.data)
	    	})
	    }
	}
</script>

