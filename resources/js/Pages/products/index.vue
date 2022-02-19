<template>
	<app-layout>
		<template #header>
			<h1 class="font-semibold text-xl text-gray-800 leading-tight">Product List</h1>
		</template>

		<div class="py-12">
			<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
				<div class="bg-white overflow-hidden shadow-xl sm:rounded-lg py-6 px-6">

					<div v-if="$page.props.flash.message && this.showAlert" class="flex p-4 mb-6 bg-green-100 rounded-lg dark:bg-green-200" role="alert">
						<svg class="flex-shrink-0 w-5 h-5 text-green-700 dark:text-green-800" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
						<div class="ml-3 text-sm font-medium text-green-700 dark:text-green-800">
							<p class="font-bold">{{ $page.props.flash.message }}</p>
						</div>
						<button @click="closeAlert()" type="button" class="ml-auto -mx-1.5 -my-1.5 bg-green-100 text-green-500 rounded-lg focus:ring-2 focus:ring-green-400 p-1.5 hover:bg-green-200 inline-flex h-8 w-8 dark:bg-green-200 dark:text-green-600 dark:hover:bg-green-300" aria-label="Close">
							<span class="sr-only">Close</span>
							<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
						</button>
					</div>


					<div class="grid grid-cols-2 gap-4">

						<div>
							<div class="justify-center">
								<Link
									:href="route('products.create')"
									class="flex-sheink bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow-md">
									Add New
								</Link>
							</div>
						</div>

						<!-- ... -->

						<div>
							<div class="justify-center">
								<search-filter v-model="form.search" class="w-full max-w-md" @reset="reset">
								</search-filter>
							</div>
						</div>

					</div>

					<table class="mt-6 min-w-full divide-y divide-gray-200">
          <thead class="bg-black">
            <tr>
              <th scope="col" class="px-6 py-3 text-left text-ms font-medium text-white font-semibold uppercase tracking-wider">
              	<Link
              		@click="sortTable('name')">
              		Name <i class="fa-solid fa-sort"></i>
              	</Link>
              </th>
              <th scope="col" class="px-6 py-3 text-left text-ms font-medium text-white font-semibold uppercase tracking-wider">
              	<Link
              		@click="sortTable('price')">
              		Price <i class="fa-solid fa-sort"></i>
             		</Link>
              </th>
              <th scope="col" class="px-6 py-3 text-left text-ms font-medium text-white font-semibold uppercase tracking-wider">
              	Brand
              </th>
              <th scope="col" class="px-6 py-3 text-left text-ms font-medium text-white font-semibold uppercase tracking-wider">Actions</th>
            </tr>
          </thead>
          <tbody class="bg-gray-500 divide-y divide-gray-200">
            <tr v-for="product in products.data" :key="product.id">
              <td class="text-white px-6 py-4 whitespace-nowrap">
              	{{product.name}}
              </td>
              <td class="text-white px-6 py-4 whitespace-nowrap">
              	{{product.price}}
              </td>
              <td class="text-white px-6 py-4 whitespace-nowrap">
              	{{product.brand}}
              </td>
              <td class="text-white px-6 py-4 whitespace-nowrap">

								<Link
								:href="route('products.edit', product.id)"
								class="flex-sheink bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg shadow-md mr-4">
								Edit
								</Link>

								<button
								@click="openModal(product)"
								class="flex-sheink bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg shadow-md">
								Delete
								</button>

              </td>
            </tr>
          </tbody>
        </table>

        <pagination class="mt-6" :links="products.links" />
					
				</div>
			</div>
		</div>

		<div v-if="showModal" class="bg-slate-800 bg-opacity-50 flex justify-center items-center absolute top-0 right-0 bottom-0 left-0">
			<div class="bg-white px-16 py-14 rounded-md text-center">
				<h1 class="text-xl mb-4 font-bold text-slate-500">Do you Want Delete</h1>
				<button @click="closeModal()" class="bg-indigo-500 px-4 py-2 rounded-md text-md text-white">Cancel</button>
				<button @click="deleteRow(this.idProduct)" class="bg-red-500 px-7 py-2 ml-2 rounded-md text-md text-white font-semibold">Ok, Delete</button>
			</div>
		</div>

	</app-layout>
</template>

<script>
	import AppLayout from "@/Layouts/AppLayout";
	import { Link } from '@inertiajs/inertia-vue3';
	import Pagination from '@/Components/Pagination';
	import pickBy from 'lodash/pickBy';
	import throttle from 'lodash/throttle';
	import mapValues from 'lodash/mapValues';
	import SearchFilter from '@/Components/SearchFilter';
	export default{
		components:{
			AppLayout,
			Link,
			Pagination,
			SearchFilter,
		},
		props:{
			filters: Object,
			products: Array,
		},
		data(){
			return{
				showAlert: true,
				showModal: false,
				idProduct: null,
				form: {
					 search: this.filters.search,
				},
				direction: 'asc',
			}
    },
		watch: {
	    form: {
	      deep: true,
	      handler: throttle(function () {
	        this.$inertia.get(route('products.index'), pickBy(this.form), { preserveState: true })
	      }, 150),
	    },
	  },
		methods: {
			closeAlert(){
				this.showAlert = false
			},
			openModal(data) {
				//this.isOpen = true;
				this.showModal = true;
				this.idProduct = data.id
			},
			closeModal() {
				//this.isOpen = false;
				this.showModal = false;
			},
			deleteRow(data) {
				this.$inertia.delete(route('products.destroy', data));
				this.closeModal();
			},
			reset() {
      	this.form = mapValues(this.form, () => null)
    	},
    	sortTable(sortby){

    		let urlParams = new URLSearchParams(window.location.search);

    		if(urlParams.get('direction') === 'asc'){
    			this.direction = 'desc';
    		}else{
    			this.direction = 'asc';
    		}

    		this.$inertia.get(route('products.index')+'?sortby='+sortby+'&direction='+this.direction);

    	},
		},
	}
</script>