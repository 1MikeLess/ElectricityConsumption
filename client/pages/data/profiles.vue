<template>
	<div class="container-fluid px-3">
		<div class="page-header">
			<h5>Пользователи 
				<!-- <span>{{max_user_key}}</span> -->
			</h5>
		</div>
		<b-table striped hover outlined dark small :fields="fields" :items="items">
			<template #btn_delete="props">
				<b-button
					class="btn-danger"
					@click="
						selectedUser.id=props.item.user_id;
						selectedUser.login=props.item.login;
						selectedUser.status=props.item.status;
						// log(props.item.user_id);
					 	$bvModal.show('remove_user_modal')"
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
			<label for="newUserLogin">Логин: </label>
			<b-form-input placeholder="Loginov" v-model="newUser.login" id="newUserLogin"></b-form-input>
			<label for="newUserEmail">EMAIL: </label>
			<b-form-input placeholder="zhuzhuk@bk.ru" v-model="newUser.email" id="newUserEmail"></b-form-input>
			<label for="newUserPass">Пароль: </label>
			<b-form-input placeholder="****" v-model="newUser.password" id="newUserPass"></b-form-input>
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
			<span>Удалить пользователя '{{selectedUser.login}}'?</span>
		</b-modal>

	</div>
</template>

<script>
	export default {
		name: 'profiles',
		data() {
			return {
				newUser: {},
				selectedUser: {},

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
					// { user_id: 0, login: 'Danilchuk', status: 'Админ' },
					// { user_id: 1, login: 'Loganov_dn', status: 'Обычный' }
				]
			}
		},
		methods: {
			log(data) {
				console.log(data)
			},
			add_user(){
				this.newUser.id = this.max_user_key+1
				this.items.push({
					user_id: this.max_user_key+1,
					login: this.newUser.login,
					status: 'Обычный'
				})
				this.$axios.post(`db_query_add_user/${this.newUser.id}/${this.newUser.login}/${this.newUser.email}/${this.newUser.password}`)

				console.log('An user has been added')
			},
			remove_user() {
				this.$axios.post('db_query_delete_user/'+this.selectedUser.id)
				this.items.pop(this.items.find(u => u.user_id === this.selectedUser.id))
				console.log('An user has been removed')
				console.log(this.selectedUser.id)
			},
		},
		computed: {
			max_user_key: {
				get: function() {
					return this.items.length>0 ? Math.max.apply(null, this.items.map(item => item.user_id)) : 0
				}
			}
		},
		mounted() {
			this.$axios.get('db_query_get_users').then(users => {
				users.data.forEach(user => {
					this.items.push({
						user_id: user.id,
						login: user.name,
					})
				})
			})
		}
	}
</script>

<style>
	
</style>