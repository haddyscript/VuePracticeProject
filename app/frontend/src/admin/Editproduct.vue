<template>
  <br><br>
  <div class="product-edit">
    <h2 class="header">Edit Product</h2>
    <form @submit.prevent="updateProduct" class="form" enctype="multipart/form-data">
      <div class="form-row">
        <div class="form-group">
          <label for="name">Product Name</label>
          <input type="text" v-model="product.name" id="name" class="form-control" />
        </div>

        <div class="form-group">
          <label for="brand">Brand</label>
          <input type="text" v-model="product.brand" id="brand" class="form-control" />
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label for="name">Material Use</label>
          <input type="text" v-model="product.material" id="name" class="form-control" />
        </div>

        <div class="form-group">
          <label for="brand">Dimensions</label>
          <input type="text" v-model="product.dimensions" id="brand" class="form-control" />
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label for="name">Manufacturer</label>
          <input type="text" v-model="product.manufacturer" id="name" class="form-control" />
        </div>

        <div class="form-group">
          <label for="weight">Weight</label>
          <input type="decimal" v-model="product.weight" @input="validateWeight"  id="weight" class="form-control" />
          <small v-if="weightError" class="text-danger">{{ weightError }}</small>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label for="price">Price</label>
          <input type="decimal" v-model="product.price" id="price" class="form-control" />
        </div>

        <div class="form-group">
          <label for="stock_quantity">Stock Quantity</label>
          <input type="number" v-model="product.stock_quantity" id="stock_quantity" class="form-control" />
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label for="color">Color</label>
          <input type="text" v-model="product.color" id="color" class="form-control" />
        </div>

        <div class="form-group">
          <label for="size">Size</label>
          <input type="text" v-model="product.size" id="size" class="form-control" />
        </div>
      </div>

      <div class="form-group">
        <label for="description">Description</label>
        <textarea v-model="product.description" id="description" class="form-control"></textarea>
      </div>

      <div class="form-group">
        <label for="image">Product Image</label>
        <input type="file" @change="handleImageChange" class="form-control" />
        <div v-if="imagePreview" class="image-preview">
          <img :src="imagePreview" alt="Product Image" />
        </div>
      </div>

      <button type="submit" class="btn" >Update Product</button>
    </form>
  </div>
</template>
  
<script>
  import apiRequest from '@/services/apiService'
  import showAlert from "@/services/swalAlert";

  export default {
    data() {
      return {
        product: {
          id: null,
          name: '',
          description: '',
          price: '',
          stock_quantity: '',
          brand: '',
          color: '',
          size: '',
          material: '',
          dimensions: '',
          manufacturer: '',
          weight: '',
          image_data: null, // This will hold the base64 image data
        },
        imagePreview: null,
        weightError: '',
      };
    },
    mounted(){
      this.created();
    },
    methods: {
      validateWeight() {
        if (!/^\d*\.?\d*$/.test(this.product.weight)) {
          this.weightError = "Please enter a valid number.";
        } else {
          this.weightError = "";
        }
      },
      created() {
          const productId = this.$route.params.id; 
          this.product.id = productId;
          this.fetchProductData(productId);
      },
      async fetchProductData(productId) {
        try {
            const response = await apiRequest.getProductById(productId);
            const product = response.data.product;
            this.product = {
                id: product.id,
                name: product.name,
                description: product.description,
                price: product.price,
                stock_quantity: product.stock_quantity,
                brand: product.brand,
                color: product.color,
                size: product.size,
                material: product.material,
                dimensions: product.dimensions,
                manufacturer: product.manufacturer,
                weight: product.weight,
                image_data: product.product_images[0]?.image_data || null,
            };
            if (this.product.image_data) {
                this.imagePreview = `data:image/jpeg;base64,${this.product.image_data}`;
            }
        } catch (error) {
            console.error('Error fetching product data', error);
        }
      },
  
      handleImageChange(event) {
        const file = event.target.files[0];
        if (file) {
          this.product.image_data = file;
          const reader = new FileReader();
          reader.onloadend = () => {
            this.imagePreview = reader.result; 
          };
          reader.readAsDataURL(file);
        }
      },
  
      async updateProduct() {

        if (!/^\d*\.?\d*$/.test(this.product.weight)) {
          return alert("Please enter a valid number.");
        }

        const formData = new FormData();
        formData.append('id', this.product.id);
        formData.append('name', this.product.name);
        formData.append('description', this.product.description);
        formData.append('price', this.product.price);
        formData.append('stock_quantity', this.product.stock_quantity);
        formData.append('brand', this.product.brand);
        formData.append('color', this.product.color);
        formData.append('size', this.product.size);
        formData.append('material', this.product.material);
        formData.append('dimensions', this.product.dimensions);
        formData.append('manufacturer', this.product.manufacturer);
        formData.append('weight', this.product.weight);
        if (this.product.image_data) {
            if (this.product.image_data instanceof File) {
                formData.append('image_data', this.product.image_data);
            }else{
                formData.append('image_data', []);
            }
        }
        console.log('Product Data:', formData);
        try {
          const response = await apiRequest.updateProductById(formData);
          console.log('Response : ', response);
          if (response.data.success == "true") {
              this.$emit('product-updated', this.product);
              showAlert("success", "Success!", response.data.message);
          } else {
              showAlert("error", "Oops!", response.data.message);
          }
        } catch (error) {
          console.error('Error updating product', error);
          alert('There was an error updating the product.');
        }
      }
    },
  };
  </script>
  

  <style scoped>
  .product-edit {
    max-width: 800px;
    margin: 2rem auto;
    background-color: #fff;
    padding: 2rem;
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
  }
  
  .header {
    text-align: center;
    font-size: 1.8rem;
    font-weight: bold;
    color: black; /* Theme color */
    margin-bottom: 1.5rem;
  }
  
  .form {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
  }
  
  .form-row {
    display: flex;
    gap: 1rem;
  }
  
  .form-group {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
  }
  
  label {
    font-weight: bold;
    color: black; 
  }
  
  input[type="text"],
  input[type="number"],
  textarea,
  input[type="file"] {
    padding: 0.8rem;
    font-size: 14px;
    border: 1px solid #ced4da;
    border-radius: 4px;
    transition: border-color 0.3s;
  }
  
  input:focus,
  textarea:focus {
    outline: none;
    border-color: #137c4d; /* Highlight on focus */
  }
  
  textarea {
    resize: vertical;
  }
  
  .image-preview {
    margin-top: 1rem;
    text-align: center;
  }
  
  .image-preview img {
    max-width: 100%;
    max-height: 200px;
    border-radius: 4px;
  }
  
  .btn {
    padding: 0.8rem;
    font-size: 1rem;
    font-weight: bold;
    color: #fff;
    background-color: #137c4d; 
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s;
  }
  
  .btn:hover {
    background-color: #105f3e;
    color: #fff;
  }
  </style>