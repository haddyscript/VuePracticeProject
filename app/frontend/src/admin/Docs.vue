<template>
    <div class="app-wrapper">
	    <br> <br>
	    <div class="app-content pt-3 p-md-3 p-lg-4">
		    <div class="container-xl">
			    <div class="row g-3 mb-4 align-items-center justify-content-between">
				    <div class="col-auto">
			            <h1 class="app-page-title mb-0">Products</h1>
				    </div>
				    <div class="col-auto">
					     <div class="page-utilities">
						    <div class="row g-2 justify-content-start justify-content-md-end align-items-center">
							    <div class="col-auto">
                    <form class="docs-search-form row gx-1 align-items-center" @submit.prevent="fetchAllProducts({ status: selectedStatus, name: searchQuery })">
                        <div class="col-auto">
                            <input type="text" id="search-docs" name="name" v-model="searchQuery" class="form-control search-docs" placeholder="Search product name">
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn app-btn-secondary">Search</button>
                        </div>
                    </form>

					                
							    </div><!--//col-->
							    <div class="col-auto">
								    
                    <select class="form-select w-auto" @change="fetchAllProducts($event.target.value)">
                      <option value="all" selected>All</option>
                      <option value="live">Live Products</option>
                      <option value="in_stock">In Stock</option>
                      <option value="out_of_stock">Out of Stock</option>
                      <option value="low_stock">Low Stock</option>
                      <option value="pre-order">Pre-Order</option>
                      <option value="active">Active</option>
                      <option value="inactive">Inactive</option>
                    </select>

							    </div>
							    <div class="col-auto">
								    <a class="btn app-btn-primary" @click="openModal" href="#"><svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-upload me-2" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
  <path fill-rule="evenodd" d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z"/>
  <path fill-rule="evenodd" d="M7.646 1.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 2.707V11.5a.5.5 0 0 1-1 0V2.707L5.354 4.854a.5.5 0 1 1-.708-.708l3-3z"/>
</svg>Upload Products</a>
							    </div>
						    </div><!--//row-->
					    </div><!--//table-utilities-->
				    </div><!--//col-auto-->
			    </div><!--//row-->
			    
			    <div class="row g-4">
				    <div v-for="product in products" :key="product.id" class="col-6 col-md-4 col-xl-3 col-xxl-2">
						<div class="app-card app-card-doc shadow-sm h-100">
						<div class="app-card-thumb-holder p-3">
							<div class="app-card-thumb">
								<img class="thumb-image" :src="'data:image/jpeg;base64,' + product.product_images[0].image_data"   alt="Product Image">
							</div>
							<a class="app-card-link-mask" href="#file-link"></a>
						</div>
						<div class="app-card-body p-3 has-card-actions">
							<!--  product properties dynamically -->
							<h4 class="app-doc-title truncate mb-0"><a href="#file-link">{{ product.name }}</a></h4>
							<div class="app-doc-meta">
							<ul class="list-unstyled mb-0">
								<li><span class="text-muted">Live:</span> {{ product.live == 1 ? 'Yes' : 'No' }}</li>
                <li><span class="text-muted">Quantity:</span> {{ product.stock_quantity }}</li>
                <li><span class="text-muted">Status:</span> {{ product.status }}</li>
                <li><span class="text-muted">Weight:</span> {{ product.weight !== null ? product.weight : 0 }} kg</li>
                <li><span class="text-muted">Dimensions:</span> {{ product.dimensions }}</li>
                <li><span class="text-muted">Edited:</span> {{ new Date(product.updated_at).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' }) }}</li>
								<li><span class="text-muted">Size:</span> {{ product.size }}</li>
                <li><span class="text-muted">Material:</span> {{ product.material }}</li>
                <li><span class="text-muted">Manufacturer:</span> {{ product.manufacturer }}</li>
							</ul>
							</div><!--//app-doc-meta-->
							<div class="app-card-actions">
								<div class="dropdown">
									<div class="dropdown-toggle no-toggle-arrow" data-bs-toggle="dropdown" aria-expanded="false">
									<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-three-dots-vertical" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
										<path fill-rule="evenodd" d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
									</svg>
									</div><!--//dropdown-toggle-->
									<ul class="dropdown-menu">
									<!-- More dropdown items as needed -->
										<li><a class="dropdown-item" href="#" @click="viewProduct(product)" >View</a></li>
										<li><router-link class="dropdown-item" :to="'/admin/edit_product/' + product.id">Edit</router-link></li>
										<li><a class="dropdown-item" @click="liveOn(product.id)">{{ product.live == 1 ? 'Live Off' : 'Live On' }}</a></li>
										<li><hr class="dropdown-divider"></li>
										<li><a class="dropdown-item" @click="deleteProduct(product.id)" href="#">Delete</a></li>
									</ul>
								</div><!--//dropdown-->
							</div><!--//app-card-actions-->
						</div><!--//app-card-body-->
						</div><!--//app-card-->
					</div><!--//col-->
				</div><!--//row-->
				
				<nav class="app-pagination mt-5" v-if="pagination.total > 0">
              <ul class="pagination justify-content-center">
                  <li class="page-item" :class="{ 'disabled': pagination.current_page === 1 }">
                      <a class="page-link" href="#" @click.prevent="changePage(pagination.current_page - 1)">Previous</a>
                  </li>
                  <li 
                      class="page-item" 
                      v-for="page in pagination.last_page" 
                      :key="page" 
                      :class="{ 'active': pagination.current_page === page }">
                      <a class="page-link" href="#" @click.prevent="changePage(page)">{{ page }}</a>
                  </li>
                  <li class="page-item" :class="{ 'disabled': pagination.current_page === pagination.last_page }">
                      <a class="page-link" href="#" @click.prevent="changePage(pagination.current_page + 1)">Next</a>
                  </li>
              </ul>
          </nav>

				
			</div><!--//container-fluid-->
		</div><!--//app-content-->
	    

		<!-- Modal -->
		<!-- Custom Modal -->
		<div
		v-if="showModalView"
		class="custom-modal-overlay"
		@click.self="closeModalView"
		>
			<div class="custom-modal-content" tabindex="0">
				<button class="close-button" @click="closeModalView">×</button>
				<div class="modal-header">
				<img
					:src="'data:image/jpeg;base64,' + selectedProduct.product_images[0].image_data"
					alt="Product Image"
					class="product-image"
				/>
				</div>
				<div class="modal-body">
				<h2>{{ selectedProduct.name }}</h2>
				<p>{{ selectedProduct.description }}</p>
				<p><strong>Price:</strong> {{ selectedProduct.price }}</p>
				<p><strong>Color:</strong> {{ selectedProduct.color }}</p>
				<p><strong>Size:</strong> {{ selectedProduct.size }}</p>
				<p><strong>Stock Quantity:</strong> {{ selectedProduct.stock_quantity }}</p>
				</div>
				<div class="modal-footer">
				<button class="action-button" @click="closeModalView()">Close</button>
				</div>
			</div>
		</div>




		<!-- Modal -->
		<div v-if="showModal" class="modal-overlay"> 
		<div v-if="showModal" class="modal fade show" tabindex="-1" style="display: block;" aria-hidden="false"> <br><br><br>
      <div class="modal-dialog modal-lg">
        <div class="modal-content shadow-sm rounded-3">
          <div class="modal-header  text-white">
            <h5 class="modal-title">Upload New Product</h5>
            <button type="button" class="btn-close text-green" @click="closeModal" aria-label="Close" style="opacity: 1; background-color: transparent; border: none; font-size: 1.5rem;">x</button>
          </div>
          <div class="modal-body p-4">
            <form @submit.prevent="submitProduct" enctype="multipart/form-data">
              <div class="row">
                <!-- Left Column -->
                <div class="col-md-6">
                  <!-- Name Input -->
                  <div class="mb-3">
                    <label for="name" class="form-label">Product Name</label>
                    <input type="text" id="name" v-model="form.name" class="form-control" placeholder="Enter product name" required />
                  </div>
                  <!-- Description Input -->
                  <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea id="description" v-model="form.description" class="form-control" placeholder="Enter product description" required></textarea>
                  </div>
                  <!-- Price Input -->
                  <div class="mb-3">
                    <label for="price" class="form-label">Price</label>
                    <input type="decimal" id="price" v-model="form.price" class="form-control" placeholder="Enter product price" required />
                  </div>
                  <!-- Color Input -->
                  <div class="mb-3">
                    <label for="color" class="form-label">Color</label>
                    <input type="text" id="color" v-model="form.color" class="form-control" placeholder="Enter product color" required />
                  </div>
                </div>

                <!-- Right Column -->
                <div class="col-md-6">

                  <!-- Size Input -->
                  <div class="mb-3">
                    <label for="size" class="form-label">Size</label>
                    <input type="text" id="size" v-model="form.size" class="form-control" placeholder="Enter product size" required />
                  </div>
                  <!-- Brand Input -->
                  <div class="mb-3">
                    <label for="brand" class="form-label">Brand</label>
                    <input type="text" id="brand" v-model="form.brand" class="form-control" placeholder="Enter product brand" required />
                  </div>
                  <!-- Stock Quantity Input -->
                  <div class="mb-3">
                    <label for="stock_quantity" class="form-label">Stock Quantity</label>
                    <input type="number" id="stock_quantity" v-model="form.stock_quantity" class="form-control" placeholder="Enter product stock quantity" required />
                  </div>
				  <div class="mb-3">
                  <label for="image" class="form-label">Product Image</label>
                  <input type="file" id="image" @change="handleImageUpload" class="form-control" accept="image/*" multiple />
                </div>
                </div>
              </div>

              <!-- Submit Button -->
              <div class="text-center">
                <button type="submit" class="btn btn-success text-white w-100">Save Product</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
	</div>


    </div><!--//app-wrapper-->    					

</template>

<script>

import apiRequest from '@/services/apiService';
import showAlert from "@/services/swalAlert";

export default {
  data() {
    return {
      showModal: false,
      showModalView : false,
      form: {
        name: '',
        description: '',
        price: '',
        quantity: '',
        color: '',
        size: '',
        brand: '',
        stock_quantity: '',
        image: null
      },
      activeDropdown: null,
      products: [],
      selectedProduct: {},
      pagination: {}
    };
  },
  mounted(){
    this.fetchAllProducts({ status: this.selectedStatus });
	
  },
  methods: {
	viewProduct(product) {
		this.selectedProduct = product; 
		this.showModalView = true; // Show the modal
		this.$nextTick(() => {
			const modalContent = document.querySelector('.custom-modal-content');
			modalContent.focus();
		});
	},
	closeModalView() {
		this.showModalView = false; 
		this.selectedProduct = {}; 
  },
  handleImageUpload(event) {
      const file = event.target.files[0];
      if (file) {
        this.form.image = file;
      }
  },
    openModal() {
      this.showModal = true;
    },
    closeModal() {
      this.showModal = false;
    },
      async fetchAllProducts(status = "all") {
        try {
          const params = { status }; 
          const response = await apiRequest.getAllProducts(params); 
          this.products = response.data.products;
          this.pagination = response.data.pagination;
          console.log("Response:", response);
        } catch (error) {
          console.error("Error fetching products:", error);
        }
      },
      changePage(page) {
          this.fetchAllProducts({ status: this.selectedStatus, name: this.searchQuery, page: page });
      },
    async submitProduct() {

      const formData = new FormData();
      formData.append("name", this.form.name);
      formData.append("description", this.form.description);
      formData.append("price", this.form.price);
      formData.append("color", this.form.color);
      formData.append("size", this.form.size);
      formData.append("brand", this.form.brand);
      formData.append("stock_quantity", this.form.stock_quantity);
      if (this.form.image) {
        formData.append("image", this.form.image);
      }

      try{

        const response = await apiRequest.addProduct(formData);

        if(response.data.success == "true"){
          showAlert("success", "Nice!", response.data.message);

          this.form = { name: '', description: '', price: '', quantity: '', color: '', size: '', brand: '', stock_quantity: '' };
          this.showModal = false;
          this.fetchAllProducts();
        }else{
          showAlert("error", "oops!", response.data.message);
        }
      }catch(error){
        console.error('Error adding product:', error);
      }
	  },
    async deleteProduct(productId) {
        try{
            const response = await apiRequest.deleteProductById(productId);
            if(response.data.success == "true"){
              showAlert("success", "Success!", response.data.message);
                this.fetchAllProducts();
              }else{
                showAlert("error", "Oops!", response.data.message);
              }
        }catch(error){
          console.error('Error deleting product:', error);
        }
    },
    async liveOn(productId) {
      try{  
          const response = await apiRequest.liveOrUnliveProduct(productId);
          if(response.data.success == "true"){
            showAlert("success", "Success!", response.data.message);
            this.fetchAllProducts();
          }else{
            showAlert("error", "Oops!", response.data.message);
          }
      }catch(error){
        console.error('Error deleting product:', error);
      }
    }
  }
};
</script>

<style scoped>

/* showModalView */
.custom-modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.8);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
  animation: fadeIn 0.3s ease;
}

.custom-modal-content {
  background: #fff;
  border-radius: 10px;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
  width: 90%;
  max-width: 500px;
  animation: slideIn 0.3s ease;
  overflow: hidden;
  position: relative;
}

.modal-header {
  text-align: center;
  padding: 15px;
  background-color: #f8f9fa;
  border-bottom: 1px solid #e9ecef;
}

.product-image {
  max-width: 100%;
  height: auto;
  border-radius: 5px;
}

.modal-body {
  padding: 20px;
  font-family: 'Arial', sans-serif;
}

.modal-body h2 {
  font-size: 1.5em;
  margin-bottom: 10px;
}

.modal-body p {
  margin: 5px 0;
  line-height: 1.5;
}

.modal-footer {
  text-align: right;
  padding: 15px;
  background-color: #f8f9fa;
  border-top: 1px solid #e9ecef;
}

.action-button {
  background-color: #007bff;
  color: white;
  border: none;
  padding: 10px 20px;
  border-radius: 5px;
  cursor: pointer;
  font-size: 1em;
}

.action-button:hover {
  background-color: #0056b3;
}

.close-button {
  position: absolute;
  top: 10px;
  right: 10px;
  background: transparent;
  border: none;
  font-size: 1.5em;
  cursor: pointer;
  color: #6c757d;
}

.close-button:hover {
  color: #000;
}

@keyframes fadeIn {
  from {
    background-color: rgba(0, 0, 0, 0);
  }
  to {
    background-color: rgba(0, 0, 0, 0.8);
  }
}

@keyframes slideIn {
  from {
    transform: translateY(-50px);
    opacity: 0;
  }
  to {
    transform: translateY(0);
    opacity: 1;
  }
}



/* Modal Background ---------------------------- */
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.7);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 999;
}
.modal {
  display: block;
  z-index: 1050;
}
.modal-backdrop {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.3);
}
.modal-content {
  border-radius: 8px;
  box-shadow: 0 4px 2px rgba(0, 0, 0, 0.1);
  background-color: #ffffff;
}
.modal-header {
  background: transparent;
  border-bottom: 1px solid #ddd;
}
.modal-title {
  font-size: 1.25rem;
  font-weight: 500;
}
.form-control {
  border-radius: 0.375rem;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
  transition: all 0.3s ease;
}
.form-control:focus {
  border-color: #15a362;
  box-shadow: 0 0 0 0.25rem rgba(21, 163, 98, 0.25);
}
.btn-success {
  background-color: #15a362;
  border-color: #15a362;
}
.btn-success:hover {
  background-color: #137c4d;
  border-color: #137c4d;
}
.btn-close {
  background: transparent;
  border: none;
  font-size: 1.5rem;
  opacity: 1;
}
</style>