<template>
	<app-layout>
		<template #header>
			<h1 class="font-semibold text-xl text-gray-800 leading-tight">Edit Brand</h1>
		</template>

		<div class="py-12">
			<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
				<div class="bg-white overflow-hidden shadow-xl sm:rounded-lg py-6 px-6">

					<form @submit.prevent="submit" class="w-full max-w-sm">

						<div class="md:flex md:items-center mb-6">
							<div class="md:w-1/3">
								<label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-4" for="name">
									Name
								</label>
							</div>
							<div class="md:w-2/3">
								<input
									class="bg-white appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:border-purple-500"
									:class = "(errors.name)?'is-invalid':''"
									id="name"
									v-model="form.name"
									type="text">
									<div class="error-message" v-if="errors.name">{{ errors.name }}</div>
							</div>
						</div>

						<div class="md:flex md:items-center">
							<div class="md:w-1/3"></div>
							<div class="md:w-2/3">

								<Link
									:href="route('brands.index')"
									class="shadow bg-red-500 hover:bg-red-700 focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded mr-4">
									Cancel
								</Link>

								<button class="shadow bg-purple-500 hover:bg-purple-400 focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded" type="submit">
									Save
								</button>
							</div>
						</div>
					</form>

					
				</div>
			</div>
		</div>

	</app-layout>
</template>

<script>
	import AppLayout from "@/Layouts/AppLayout";
	import { Link } from '@inertiajs/inertia-vue3';
	export default{
		components:{
			AppLayout,
			Link
		},
		props:{
			brand:Object,
			errors: Object
		},
		data() {
			return {
				form: {
					name: this.$props.brand.name,
				},
			};
		},
		methods:{
			submit(){
				this.$inertia.put(route('brands.update', this.$props.brand.id), this.form);
			}
		}

	}
</script>