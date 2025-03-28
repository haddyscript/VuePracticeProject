<template>
    <!-- Start Hero Section -->
			<div class="hero">
				<div class="container">
					<div class="row justify-content-between">
						<div class="col-lg-5">
							<div class="intro-excerpt">
								<h1>Shop</h1>
							</div>
						</div>
						<div class="col-lg-7">
							
						</div>
					</div>
				</div>
			</div>
		<!-- End Hero Section -->

		<div v-if="isLoading" class="loader-container">
			<div class="loader"></div>
		</div>
		<div v-else>
		<div class="untree_co-section product-section before-footer-section">
			<div class="container">
				<div class="row">
					<div v-for="product in products" :key="product.id" class="col-12 col-md-4 col-lg-3 mb-5">
						<a class="product-item" href="#" @click.prevent="openModal(product)">
							<img :src="getProductImage(product)" class="img-fluid product-thumbnail" alt="Product Image" style="max-width: 300px; height: 300px; margin: 0 auto; display: block;">
							<!-- Yellow Badge (Only if product is in cart) -->
							<span v-if="product.is_in_cart.length > 0" class="badge bg-warning position-absolute top-0 start-100 translate-middle">
								{{ product.is_in_cart[0].quantity }} qty in cart
							</span>
							<h3 class="product-title">{{ product.name }}</h3>
							<strong class="product-price">${{ formatPrice(product.price) }}</strong>
							<p class="product-brand">Brand: {{ product.brand }}</p>
							<p class="product-quantity">Quantity: {{ product.stock_quantity }}</p>
							<p class="product-date">Added on: {{ formatDate(product.created_at) }}</p>

							<span class="icon-cross">
								<img src="/images/cross.svg" class="img-fluid">
							</span>
						</a>
					</div>
				</div>
			</div>

			<nav class="app-pagination mt-5" v-if="pagination.total > 0">
				<ul class="pagination justify-content-center">
					<!-- Previous Button -->
					<li class="page-item" :class="{ 'disabled': pagination.current_page === 1 }">
						<a class="page-link" href="#" @click.prevent="changePage(pagination.current_page - 1)" aria-label="Previous">
							<span aria-hidden="true">&laquo;</span> Previous
						</a>
					</li>
					
					<!-- Page Number Buttons -->
					<li 
						class="page-item" 
						v-for="page in pagination.last_page" 
						:key="page" 
						:class="{ 'active': pagination.current_page === page, 'disabled': pagination.current_page === page }">
						<a class="page-link" href="#" @click.prevent="changePage(page)">{{ page }}</a>
					</li>

					<!-- Next Button -->
					<li class="page-item" :class="{ 'disabled': pagination.current_page === pagination.last_page }">
						<a class="page-link" href="#" @click.prevent="changePage(pagination.current_page + 1)" aria-label="Next">
							Next <span aria-hidden="true">&raquo;</span>
						</a>
					</li>
				</ul>
			</nav>
		</div>
		</div>

		<!-- Modal -->
		<div v-if="showModal" class="modal fade show" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true" style="display: block;">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="productModalLabel">{{ selectedProduct.name }}</h5>
						<button type="button" class="close" @click="closeModal" aria-label="Close" style="border: none; background: transparent; font-size: 1.5rem; color: #000; opacity: 0.7; transition: opacity 0.3s ease;">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<img :src="getProductImage(selectedProduct)" class="img-fluid mb-3" alt="Product Image" style="max-width: 300px; height: auto; margin: 0 auto; display: block;">
						<p> {{ selectedProduct.description }}</p>
						<p><strong>Price:</strong> ${{ formatPrice(selectedProduct.price) }}</p>
						<p><strong>Brand:</strong> {{ selectedProduct.brand }}</p>
						<p><strong>Quantity:</strong> {{ selectedProduct.stock_quantity }}</p>
						<p><strong>Color:</strong> {{ selectedProduct.color }}</p>
						<p><strong>Material:</strong> {{ selectedProduct.material==='null' ? ' ' : selectedProduct.material }}</p>
						<p><strong>Size:</strong> {{ selectedProduct.size }}</p>
						<p><strong>Added on:</strong> {{ formatDate(selectedProduct.created_at) }}</p>
					</div>
					<div class="modal-footer">
						<input type="number" class="form-control" name="quantity" v-model="quantity" placeholder="Quantity" style="width: 100px;">
						<button type="button" class="btn btn-primary" @click="addToCart(selectedProduct.id, quantity)">Add to Cart</button>
					</div>
				</div>
			</div>
		</div>



</template>

<script>
import apiRequest from '@/services/apiService';
import showAlert from "@/services/swalAlert";

export default {
	data() {
		return {
			isLoading: true,
			products: [],
			pagination: {},
			showModal: false,
      		electedProduct: {},
			quantity: 1,
		}
	},
	mounted() {
		this.fetchProducts();
		setTimeout(() => {
			this.isLoading = false; 
		}, 1000);
	},
	methods : {
		openModal(product) {
			this.selectedProduct = product;
			this.showModal = true;
		},
		closeModal() {
			this.showModal = false;
			this.selectedProduct = {};
		},
		changePage(page) {
			this.fetchProducts({ status: this.selectedStatus, name: this.searchQuery, page: page });
		},
		async fetchProducts(status = {}) {

			try{
				const params = { status };
				const response = await apiRequest.user_getAllProducts(params);
				if(response.data.success == "true"){
					this.products = response.data.products || [];
					this.pagination = response.data.pagination;
				}else{
					console.error('Error fetching products', response.data);
				}
			}catch(error){
				console.error('Catch error ' , error);
			}
		},
		formatDate(dateString) {
			if (!dateString) return "Unknown date";
			const date = new Date(dateString);
			return date.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
		},
		formatPrice(price) {
			return parseFloat(price).toFixed(2);
   	 	},
		getProductImage(product) {
			return 'data:image/jpeg;base64,' +product.product_images[0].image_data || '/images/default-product.png';
		},
		async addToCart(productId, quantity) {
			const user = localStorage.getItem('user');
			var isLogin = user !== null ? true : false;
			this.closeModal();

			try{
				const formData = new FormData();
				formData.append('product_id', productId);
				formData.append('quantity', quantity);
				formData.append('isLogin', isLogin);

				const response =  await apiRequest.addToCart(formData);

				if(response.data.success == "true"){
					showAlert("success", "Success!", response.data.message);
					this.$router.push('/cart');
					this.quantity = 1;
				}else{
					showAlert("error", "Oops!", response.data.message);
					this.quantity = 1;
				}

			}catch(error){
				console.error('Error adding product to cart', error);
			}

		},
	}
}
</script>

<style>
/* This is for navigation buttons for pagination in the shop page */
.app-pagination .page-link {
    border-radius: 50px; 
    padding: 10px 20px; 
    font-size: 13px; 
    background-color: #3b5d50;
    color: white;
    transition: all 0.3s ease;
}

.app-pagination .page-link:hover {
    background-color: white;
    color: #000;
    text-decoration: none;
	border: #28a745 solid 1px;
}

.app-pagination .page-item.active .page-link {
    background-color: #28a745;
    border-color: #28a745;
}

.app-pagination .page-item.disabled .page-link {
    background-color: #f8f9fa;
    color: #6c757d;
    cursor: not-allowed;
}





.loader-container {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh; /* Full page */
  /* background-color: rgba(255, 255, 255, 0.8); */
  background-color: white;
  position: fixed;
  width: 100%;
  top: 0;
  left: 0;
  z-index: 999;
}

.loader {
  border: 5px solid #f3f3f3;
  border-top: 5px solid #3b5d50;
  border-radius: 50%;
  width: 50px;
  height: 50px;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}


</style>