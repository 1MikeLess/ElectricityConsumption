<template>
	<div class="container-fluid px-3">
		<div class="page-header">
			<h5>Оборудование</h5>
		</div>
		<b-table striped hover outlined dark small :fields="fields" :items="items"></b-table>
	</div>
</template>

<script>
	export default {
		name: 'equipment',
		data() {
			return {
				fields: {
					login: {
						label: 'Логин',
						sortable: true
					}, 
					status: {
						label: 'Статус',
						sortable: true
					},
					btn_delete: {
						label: 'Удалить',
						variant: 'danger'
					}
				},
				items: [
					{ login: 'Danilchuk', status: 'Админ' },
					{ login: 'Loganov_dn', status: 'Обычный' }
				]
			}
		}
	}
</script>

<style>
	
</style>