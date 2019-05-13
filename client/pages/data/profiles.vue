<template>
	<div class="container-fluid px-3">
		<div class="page-header">
			<h5>Пользователи {{max_user_key}}</h5>
		</div>
		<b-table striped hover outlined dark small :fields="fields" :items="items">
			<template #btn_delete="props">
				<b-button
					class="btn-danger"
					@click="removing_user_model=props.item.login; $bvModal.show('remove_user_modal')"
				>Удалить</b-button>
			</template>
		</b-table>

		<b-button
			class="btn-secondary"
			@click="$bvModal.show('add_user_modal')"
		>Добавить пользователя</b-button>

		<b-modal
			id="add_user_modal"
			header-bg-variant="success"
			header-text-variant="light"
			title="Добавить нового пользователя"
			ok="Подтвердить"
			cancelTitle="Отмена"
			ok-variant="success"
			@ok="add_user"
		>
			<span>Логин: </span>
			<b-form-input placeholder="Loginov" v-model="new_user_model"></b-form-input>
		</b-modal>

		<b-modal
			id="remove_user_modal"
			header-bg-variant="danger"
			header-text-variant="light"
			ok="Подтвердить"
			cancelTitle="Отмена"
			ok-variant="danger"
			@ok="remove_user"
		>
			<span>Удалить пользователя '{{removing_user_name}}'?</span>
		</b-modal>

	</div>
</template>

<script>
	export default {
		name: 'profiles',
		data() {
			return {
				new_user_model: null,

				removing_user_name: null,
				removing_user_id: null,

				fields: {
					user_id: {
						label: 'ID',
						sortable: true
					},
					login: {
						label: 'Логин',
						sortable: true
					}, 
					status: {
						label: 'Статус',
						sortable: true
					},
					btn_delete: {
						label: 'Удалить'
					}
				},
				items: [
					{ user_id: 0, login: 'Danilchuk', status: 'Админ' },
					{ user_id: 1, login: 'Loganov_dn', status: 'Обычный' }
				]
			}
		},
		methods: {
			add_user(){
				this.items.push({
					user_id: this.max_user_key+1,
					login: this.new_user_model,
					status: 'Обычный'
				})
				console.log('An user has been added')
			},
			remove_user() {
				this.items.splice(this.removing_user_id, 1)
				console.log('An user has been removed')
			},
		},
		computed: {
			max_user_key: {
				get: function() {
					return this.items.length>0 ? Math.max.apply(null, this.items.map(item => item.user_id)) : 0
				}
			}
		}
	}
</script>

<style>
	
</style>